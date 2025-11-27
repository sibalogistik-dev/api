<?php

namespace App\Services;

use App\Models\Karyawan;
use App\Models\Payroll;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function create(array $data)
    {
        $month = $data['month'] ?? now()->format('Y-m');
        try {
            DB::transaction(function () use ($month) {
                $calculatedPayrolls = $this->calculatePayroll($month);
                return $calculatedPayrolls;
            });
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(Payroll $payroll, array $data)
    {
        return DB::transaction(function () use ($payroll, $data) {
            try {
                $payroll->update($data);
                return $payroll;
            } catch (Exception $e) {
                throw new Exception('Failed to update payroll data: ' . $e->getMessage());
            }
        });
    }

    private function calculatePayroll($month)
    {
        $periodStart    = Carbon::parse("$month-28")->subMonth()->startOfDay();
        $periodEnd      = Carbon::parse("$month-27")->endOfDay();
        $monthsDays     = $this->countDaysInPeriod($month, true);

        $period = [
            'start' => $periodStart,
            'end'   => $periodEnd,
        ];
        $periodName         = 'Payroll ' . $periodEnd->locale('id')->translatedFormat('F Y');
        $employees          = Karyawan::all();
        $totalEmployees     = $employees->count();
        $payrollData        = Payroll::where('period_start', $period['start'])->get();
        $totalPayrollData   = $payrollData->count();

        if ($totalEmployees == $totalPayrollData) {
            throw new Exception('Payroll data for this month already exists');
        }

        Payroll::where('period_start', $period['start'])->forceDelete();
        $dataPayroll = [];

        foreach ($employees as $employee) {
            if ($employee->salaryDetails->salary_type == 'monthly') {
                $dataPayroll[] = $this->calculateMonthlyPayroll($employee, $period, $periodName, $monthsDays);
            } else {
                $dataPayroll[] = $this->calculateDailyPayroll($employee, $period, $periodName);
            }
        }

        Payroll::insert($dataPayroll);
        return $dataPayroll;
    }

    // private function calculatePayrollP() : Returntype {

    // }

    private function calculateMonthlyPayroll($employee, $period, $periodName, $monthsDays)
    {
        $base_salary    = [
            'daily'             => $employee->salaryDetails->daily_base_salary,
            'monthly'           => $employee->salaryDetails->monthly_base_salary,
            'allowance'         => $employee->salaryDetails->allowance,
            'meal_allowance'    => $employee->salaryDetails->meal_allowance,
            'bonus'             => $employee->salaryDetails->bonus,
            'overtime'          => $employee->salaryDetails->overtime,
            'salary_type'       => $employee->salaryDetails->salary_type,
        ];

        $deduction      = 0;

        $maxDays        = 26;
        $attendances    = $employee->attendance()
            ->whereBetween('date', [$period['start'], $period['end']])
            ->orderBy('late_arrival_time', 'desc')
            ->get();
        $overtime       = $employee->overtime()
            ->whereBetween('start_time', [$period['start'], $period['end']])
            ->get();

        $totalAttendanceDays    = $attendances->count();
        $totalPresentDays       = $attendances->where('attendance_status_id', 1)->count();
        $totalHalfDays          = $attendances->where('attendance_status_id', 1)->where('half_day', true)->count();
        $totalPermissionDays    = $attendances->where('attendance_status_id', 2)->count();
        $totalSickDays          = $attendances->where('attendance_status_id', 3)->where('sick_note', true)->count();
        $totalAbsentDays        = $attendances->where('attendance_status_id', 4)->count() + $attendances->where('attendance_status_id', 3)->where('sick_note', false)->count();
        $totalLeaveDays         = $attendances->where('attendance_status_id', 5)->count();
        $totalOffDays           = $attendances->where('attendance_status_id', 6)->count();
        $overtimeMinutes        = $this->calculateOvertimeMinutes($overtime);
        $lateMinutes            = $attendances->sum('late_arrival_time');

        if ($monthsDays > $totalAttendanceDays) {
            $totalAbsentDays += $monthsDays - $totalAttendanceDays;
        }

        $allowance      = $this->calculateAllowance($base_salary, $maxDays);

        $deduction      += $this->calculateHalfDays($base_salary, min($totalHalfDays, $maxDays));
        $deduction      += $this->calculateFullDaySalary($base_salary,  min($totalPermissionDays, $maxDays));
        $deduction      += $this->calculateAllowance($base_salary, min($totalSickDays, $maxDays));
        $deduction      += $this->calculateFullDaySalary($base_salary, min($totalAbsentDays, $maxDays));
        $deduction      += $this->calculateAllowance($base_salary, min($totalLeaveDays, $maxDays));
        $deduction      += $this->calculateFullDaySalary($base_salary, min($totalOffDays, $maxDays));
        $deduction      += $this->calculateLate($lateMinutes);
        if ($totalAttendanceDays < $maxDays) {
            $deduction  += $this->calculateFullDaySalary($base_salary,  1);
        }
        $overtimePay    = $this->calculateOvertimePay($base_salary, ceil($overtimeMinutes));

        $compensation   = $this->calculateCompensation($employee, $attendances);

        $netSalary      = max(0, $base_salary['monthly'] + $allowance + $overtimePay - $deduction + $compensation);

        $payroll = [
            'employee_id'               => $employee->id,
            'period_name'               => $periodName,
            'period_start'              => $period['start'],
            'period_end'                => $period['end'],
            'salary_type'               => $base_salary['salary_type'],
            'base_salary'               => $base_salary['monthly'],
            'days'                      => $monthsDays,
            'present_days'              => $totalPresentDays,
            'half_days'                 => $totalHalfDays,
            'absent_days'               => $totalAbsentDays,
            'sick_days'                 => $totalSickDays,
            'leave_days'                => $totalLeaveDays,
            'permission_days'           => $totalPermissionDays,
            'off_days'                  => $totalOffDays,
            'overtime_minutes'          => $overtimeMinutes,
            'late_minutes'              => $lateMinutes,
            'deductions'                => $deduction,
            'allowances'                => $allowance,
            'overtime'                  => $overtimePay,
            'compensation'              => $compensation,
            'net_salary'                => $netSalary,
            'generated_at'              => now(),
            'created_at'                => now(),
            'updated_at'                => now(),
        ];
        return $payroll;
    }

    private function calculateDailyPayroll($employee, $period, $periodName)
    {
        $base_salary    = [
            'daily'             => $employee->salaryDetails->daily_base_salary,
            'monthly'           => $employee->salaryDetails->monthly_base_salary,
            'allowance'         => $employee->salaryDetails->allowance,
            'meal_allowance'    => $employee->salaryDetails->meal_allowance,
            'bonus'             => $employee->salaryDetails->bonus,
            'overtime'          => $employee->salaryDetails->overtime,
            'salary_type'       => $employee->salaryDetails->salary_type,
        ];

        $deduction      = 0;

        $attendances    = $employee->attendance()
            ->whereBetween('date', [$period['start'], $period['end']])
            ->orderBy('late_arrival_time', 'desc')
            ->get();
        $overtime       = $employee->overtime()
            ->whereBetween('start_time', [$period['start'], $period['end']])
            ->get();

        $totalAttendanceDays    = $attendances->count();
        $totalPresentDays       = $attendances->where('attendance_status_id', 1)->count();
        $totalHalfDays          = $attendances->where('attendance_status_id', 1)->where('half_day', true)->count();
        $totalPermissionDays    = $attendances->where('attendance_status_id', 2)->count();
        $totalSickDays          = $attendances->where('attendance_status_id', 3)->count();
        $totalAbsentDays        = $attendances->where('attendance_status_id', 4)->count();
        $totalLeaveDays         = $attendances->where('attendance_status_id', 5)->count();
        $totalOffDays           = $attendances->where('attendance_status_id', 6)->count();
        $overtimeMinutes        = $this->calculateOvertimeMinutes($overtime);
        $lateMinutes            = $attendances->sum('late_arrival_time');

        $baseSalary = $base_salary['daily'] * $totalAttendanceDays;

        $allowance  = $this->calculateAllowance($base_salary, $totalAttendanceDays);

        $deduction  += $this->calculateHalfDays($base_salary, $totalHalfDays);
        $deduction  += $this->calculateFullDaySalary($base_salary, $totalPermissionDays);
        $deduction  += $this->calculateFullDaySalary($base_salary, $totalSickDays);
        $deduction  += $this->calculateFullDaySalary($base_salary, $totalAbsentDays);
        $deduction  += $this->calculateAllowance($base_salary, $totalLeaveDays);
        $deduction  += $this->calculateFullDaySalary($base_salary, $totalOffDays);
        $deduction  += $this->calculateLate($lateMinutes);

        $overtimePay    = $this->calculateOvertimePay($base_salary, ceil($overtimeMinutes));

        $compensation   = $this->calculateCompensation($employee, $attendances);

        $netSalary      = max(0, $baseSalary + $allowance + $overtimePay - $deduction + $compensation);

        $payroll = [
            'employee_id'               => $employee->id,
            'period_name'               => $periodName,
            'period_start'              => $period['start'],
            'period_end'                => $period['end'],
            'salary_type'               => $base_salary['salary_type'],
            'base_salary'               => $baseSalary,
            'days'                      => $totalAttendanceDays,
            'present_days'              => $totalPresentDays,
            'half_days'                 => $totalHalfDays,
            'absent_days'               => $totalAbsentDays,
            'sick_days'                 => $totalSickDays,
            'leave_days'                => $totalLeaveDays,
            'permission_days'           => $totalPermissionDays,
            'off_days'                  => $totalOffDays,
            'overtime_minutes'          => $overtimeMinutes,
            'late_minutes'              => $lateMinutes,
            'deductions'                => $deduction,
            'allowances'                => $allowance,
            'overtime'                  => $overtimePay,
            'compensation'              => $compensation,
            'net_salary'                => $netSalary,
            'generated_at'              => now(),
            'created_at'                => now(),
            'updated_at'                => now(),
        ];
        return $payroll;
    }

    private function calculateOvertimeMinutes($overtime)
    {
        $overtimeMinutes = 0;
        foreach ($overtime as $ot) {
            $start          = Carbon::parse($ot->start_time);
            $end            = Carbon::parse($ot->end_time);
            $diffMinutes    = $start->diffInMinutes($end);
            $overtimeMinutes += $diffMinutes;
        }
        return ceil($overtimeMinutes);
    }

    private function calculateLate($totalLateMinutes)
    {
        return $totalLateMinutes * 1000;
    }

    private function countDaysInPeriod($month, $excludeSundays = false)
    {
        $start = Carbon::parse("$month-28")->subMonth()->startOfDay();
        $end   = Carbon::parse("$month-27")->endOfDay();

        $days = 0;
        $current = $start->copy();

        while ($current->lte($end)) {
            if ($excludeSundays) {
                if ($current->dayOfWeek !== Carbon::SUNDAY) {
                    $days++;
                }
            } else {
                $days++;
            }
            $current->addDay();
        }

        return $days;
    }

    private function calculateHalfDays($baseSalary, $totalDay)
    {
        return ($baseSalary['daily'] * $totalDay) / 2;
    }

    private function calculateAllowance($baseSalary, $totalDay)
    {
        return ($baseSalary['allowance'] + $baseSalary['meal_allowance'] + $baseSalary['bonus']) * $totalDay;
    }

    private function calculateFullDaySalary($baseSalary, $totalDay)
    {
        return ($baseSalary['daily'] + $baseSalary['allowance'] + $baseSalary['meal_allowance'] + $baseSalary['bonus']) * $totalDay;
    }

    public function calculateOvertimePay($baseSalary, $totalOvertimeMinutes)
    {
        return $baseSalary['overtime'] * $totalOvertimeMinutes;
    }

    private function calculateCompensation($employee, $attendances)
    {
        $totalLateMinutes = 0;
        $user = $employee->user;
        if ($user && $user->hasRole('Manager')) {
            $topLates = $attendances->take(3);
            $totalLateMinutes = $topLates->sum('late_arrival_time');
        } else {
            $topLate = $attendances->first();
            if ($topLate) {
                $totalLateMinutes = $topLate->late_arrival_time;
            }
        }

        return $this->calculateLate($totalLateMinutes);
    }
}
