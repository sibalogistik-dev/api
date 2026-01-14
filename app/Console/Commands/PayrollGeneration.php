<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\Overtime;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PayrollGeneration extends Command
{
    protected $signature = 'app:payroll-generation {month? : Format YYYY-MM (default: current month)} {employee_id? : Optional employee ID to generate payroll for a specific employee}';
    protected $description = 'Generate payroll for all employees';

    public function handle($month = null, $employee_id = null)
    {
        $month          = $month ?? $this->argument('month') ?? now()->format('Y-m');
        $employee_id    = $employee_id ?? $this->argument('employee_id') ?? null;
        $periodStart    = Carbon::parse("$month-28")->subMonth()->startOfDay();
        $periodEnd      = Carbon::parse("$month-27")->endOfDay();
        $monthsDays     = $this->countDaysInPeriod($month, true);

        $period         = [
            'start' => $periodStart->format('Y-m-d'),
            'end'   => $periodEnd->format('Y-m-d'),
        ];
        $periodName     = 'Payroll ' . $periodEnd->locale('id')->translatedFormat('F Y');
        $this->info("Generating $periodName (" . $periodStart->locale('id')->translatedFormat('d F Y') . " → " . $periodEnd->locale('id')->translatedFormat('d F Y') . ")");
        $this->info("Total days in period (excluding Sundays): $monthsDays");

        DB::transaction(function () use ($period, $periodName, $monthsDays, $employee_id) {

            if ($employee_id !== null) {
                $employees  = Karyawan::where('id', $employee_id)->get();
            } else {
                $employees  = Karyawan::all();
            }

            foreach ($employees as $employee) {
                $attendances    = Absensi::where('employee_id', $employee->id)
                    ->whereBetween('date', [$period['start'], $period['end']])
                    ->orderBy('late_arrival_time', 'desc')
                    ->get();
                $overtime       = Overtime::where('employee_id', $employee->id)
                    ->whereBetween('start_time', [$period['start'], $period['end']])
                    ->get();
                if ($employee->salaryDetails->salary_type == 'monthly') {
                    $this->calculateMonthlyPayroll($employee, $attendances, $overtime, $period, $periodName, $monthsDays);
                } else if ($employee->salaryDetails->salary_type == 'daily') {
                    $this->calculateDailyPayroll($employee, $attendances, $overtime, $period, $periodName);
                }
            }
        });
    }

    private function calculateMonthlyPayroll($employee, $attendances, $overtime, $period, $periodName, $periodDays)
    {
        $base_salary    = [
            'daily'             => $employee->salaryDetails->daily_base_salary,
            'monthly'           => $employee->salaryDetails->monthly_base_salary,
            'allowance'         => $employee->salaryDetails->allowance,
            'meal_allowance'    => $employee->salaryDetails->meal_allowance,
            'bonus'             => $employee->salaryDetails->bonus,
            'overtime'          => $employee->salaryDetails->overtime,
        ];

        $maxDays                = 26;
        $totalAttendanceData    = $attendances->count();

        $netSalary              = 0;
        $deduction              = 0;
        $allowance              = 0;
        $lateMinutes            = $attendances->sum('late_arrival_time');
        $overtimeMinutes        = $this->calculateOvertimeMinutes($overtime);
        $overtimePay            = $this->calculateOvertimePay($overtime, $base_salary['overtime']);

        $attendanceDays         = 0; // hadir
        $absentDays             = 0; // tanpa keterangan
        $halfDays               = 0; // setengah hari
        $permissionDays         = 0; // izin
        $sickDays               = 0; // sakit
        $leaveDays              = 0; // cuti
        $offDays                = 0; // libur

        foreach ($attendances as $attendance) {
            $statusAbsensi  = $attendance->attendance_status_id;
            if ($statusAbsensi == 1) {
                if ($attendance->half_day) {
                    $halfDays++;
                } else {
                    $attendanceDays++;
                }
            }
            if ($statusAbsensi == 2) {
                $permissionDays++;
            }
            if ($attendance->attendance_status_id === 3) {
                if ($attendance->sick_note) {
                    $sickDays++;
                } else {
                    $absentDays++;
                }
            }
            if ($statusAbsensi == 4) {
                $absentDays++;
            }
            if ($statusAbsensi == 5) {
                $leaveDays++;
            }
            if ($statusAbsensi == 6) {
                $offDays++;
            }
        }

        if ($totalAttendanceData !== $periodDays) {
            $absentDays += ($periodDays - $totalAttendanceData);
        }

        $deduction  += $this->calculateLate($lateMinutes);
        $deduction  += $this->calculateFull($base_salary, $absentDays);
        $deduction  += $this->calculateHalfDay($base_salary, $halfDays);
        $deduction  += $this->calculateFull($base_salary, $permissionDays);
        $deduction  += $this->calculateAllowance($base_salary, $sickDays);
        $deduction  += $this->calculateAllowance($base_salary, $leaveDays);
        $deduction  += $this->calculateFull($base_salary, $offDays);

        if ($periodDays > $maxDays || $periodDays < $maxDays) {
            $allowance = $this->calculateAllowance($base_salary, $maxDays);
        } else {
            $allowance = $this->calculateAllowance($base_salary, $periodDays);
        }

        $lateCompensation = $this->calculateCompensation($employee, $attendances);

        $netSalary = $base_salary['monthly'] + $allowance + $overtimePay - $deduction + $lateCompensation;

        $payroll = [
            'employee_id'               => $employee->id,
            'period_name'               => $periodName,
            'period_start'              => $period['start'],
            'period_end'                => $period['end'],
            'salary_type'               => 'monthly',
            'base_salary'               => $base_salary['monthly'],
            'days'                      => $periodDays,
            'present_days'              => $attendanceDays,
            'half_days'                 => $halfDays,
            'absent_days'               => $absentDays,
            'sick_days'                 => $sickDays,
            'leave_days'                => $leaveDays,
            'permission_days'           => $permissionDays,
            'off_days'                  => $offDays,
            'overtime_minutes'          => $overtimeMinutes,
            'late_minutes'              => $lateMinutes,
            'deductions'                => $deduction,
            'allowances'                => $allowance,
            'overtime'                  => $overtimePay,
            'compensation'              => $lateCompensation,
            'net_salary'                => $netSalary,
            'generated_at'              => now(),
        ];
        Payroll::create($payroll);
        $this->info("Total data absensi {$totalAttendanceData} / {$periodDays} untuk karyawan {$employee->name}");
    }

    private function calculateDailyPayroll($employee, $attendances, $overtime, $period, $periodName)
    {
        $base_salary    = [
            'daily'             => $employee->salaryDetails->daily_base_salary,
            'allowance'         => $employee->salaryDetails->allowance,
            'meal_allowance'    => $employee->salaryDetails->meal_allowance,
            'bonus'             => $employee->salaryDetails->bonus,
            'overtime'          => $employee->salaryDetails->overtime,
        ];

        $totalAttendanceData    = $attendances->count();

        $baseSalary             = 0;
        $netSalary              = 0;
        $deduction              = 0;
        $allowance              = 0;
        $lateMinutes            = $attendances->sum('late_arrival_time');
        $overtimeMinutes        = $this->calculateOvertimeMinutes($overtime);
        $overtimePay            = $this->calculateOvertimePay($overtime, $base_salary['overtime']);

        $attendanceDays         = 0; // hadir
        $absentDays             = 0; // tanpa keterangan
        $halfDays               = 0; // setengah hari
        $permissionDays         = 0; // izin
        $sickDays               = 0; // sakit
        $leaveDays              = 0; // cuti
        $offDays                = 0; // libur

        foreach ($attendances as $attendance) {
            $statusAbsensi  = $attendance->attendance_status_id;
            if ($statusAbsensi == 1) {
                if ($attendance->half_day) {
                    $halfDays++;
                } else {
                    $attendanceDays++;
                }
            }
            if ($statusAbsensi == 2) {
                $permissionDays++;
            }
            if ($attendance->attendance_status_id === 3) {
                $absentDays++;
            }
            if ($statusAbsensi == 4) {
                $absentDays++;
            }
            if ($statusAbsensi == 5) {
                $leaveDays++;
            }
            if ($statusAbsensi == 6) {
                $offDays++;
            }
        }

        $deduction  += $this->calculateLate($lateMinutes);
        $deduction  += $this->calculateFull($base_salary, $absentDays);
        $deduction  += $this->calculateHalfDay($base_salary, $halfDays);
        $deduction  += $this->calculateFull($base_salary, $permissionDays);
        $deduction  += $this->calculateAllowance($base_salary, $sickDays);
        $deduction  += $this->calculateAllowance($base_salary, $leaveDays);
        $deduction  += $this->calculateFull($base_salary, $offDays);

        $allowance  = $this->calculateAllowance($base_salary, $attendanceDays);

        $baseSalary = $this->calculateDailyBaseSalary($base_salary, $totalAttendanceData);

        $lateCompensation = $this->calculateCompensation($employee, $attendances);

        $netSalary  = $baseSalary + $allowance + $overtimePay - $deduction + $lateCompensation;

        $payroll = [
            'employee_id'               => $employee->id,
            'period_name'               => $periodName,
            'period_start'              => $period['start'],
            'period_end'                => $period['end'],
            'salary_type'               => 'daily',
            'base_salary'               => $baseSalary,
            'days'                      => $totalAttendanceData,
            'present_days'              => $attendanceDays,
            'half_days'                 => $halfDays,
            'absent_days'               => $absentDays,
            'sick_days'                 => $sickDays,
            'leave_days'                => $leaveDays,
            'permission_days'           => $permissionDays,
            'off_days'                  => $offDays,
            'overtime_minutes'          => $overtimeMinutes,
            'late_minutes'              => $lateMinutes,
            'deductions'                => $deduction,
            'allowances'                => $allowance,
            'overtime'                  => $overtimePay,
            'compensation'              => $lateCompensation,
            'net_salary'                => $netSalary,
            'generated_at'              => now(),
        ];
        Payroll::create($payroll);
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

    private function calculateDailyBaseSalary($base_salary, $attendanceDays)
    {
        return $base_salary['daily'] * $attendanceDays;
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
        return $overtimeMinutes;
    }

    private function calculateOvertimePay($overtime, $minutelyRate)
    {
        $totalOvertimePay = 0;
        $overtimeMinutes    = $this->calculateOvertimeMinutes($overtime);
        $totalOvertimePay = (int)($overtimeMinutes * $minutelyRate);
        return $totalOvertimePay;
    }

    private function calculateLate($totalMinutes)
    {
        $decrement = 1000;
        return $totalMinutes * $decrement;
    }

    private function calculateFull($base_salary, $totalDays)
    {
        return ($base_salary['daily'] + $base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    private function calculateHalfDay($base_salary, $totalDays)
    {
        return (((int) $base_salary['daily'] / 2) * $totalDays);
    }

    private function calculateAllowance($base_salary, $totalDays)
    {
        return ($base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    private function calculateCompensation($employee, $attendances)
    {
        $totalLateMinutes = 0;

        // pastikan relasi user tersedia
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
