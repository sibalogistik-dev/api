<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\Overtime;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GeneratePayrollCommand extends Command
{
    protected $signature = 'payroll:generate {month? : Format YYYY-MM (default: current month)}';
    protected $description = 'Generate payroll for all employees using attendance data (cutoff 28 → 27)';

    public function handle()
    {
        $month          = $this->argument('month') ?? now()->format('Y-m');
        $periodStart    = Carbon::parse("$month-28")->subMonth()->startOfDay();
        $periodEnd      = Carbon::parse("$month-27")->endOfDay();

        $period = [
            'start' => $periodStart->format('Y-m-d'),
            'end'   => $periodEnd->format('Y-m-d'),
        ];
        $periodName     = 'Payroll ' . $periodEnd->locale('id')->translatedFormat('F Y');
        $this->info("Generating $periodName ($periodStart → $periodEnd)");

        DB::transaction(function () use ($periodStart, $periodEnd, $periodName, $period) {
            $employees  = Karyawan::all();

            foreach ($employees as $employee) {
                $attendances    = Absensi::where('employee_id', $employee->id)
                    ->whereBetween('date', [$periodStart, $periodEnd])
                    ->get();
                $overtimes      = Overtime::where('employee_id', $employee->id)
                    ->whereBetween('start_time', [$periodStart, $periodEnd])
                    ->where('approved', true)
                    ->get();
                if ($employee->salaryDetails->salary_type == 'monthly') {
                    $this->calculateMonthlyPayroll($employee, $attendances, $overtimes, $period, $periodName);
                } else {
                    $this->calculateDailyPayroll($employee, $attendances, $overtimes, $period, $periodName);
                }
            }
        });

        $this->info('Payroll generation complete.');
    }

    private function calculateMonthlyPayroll($employee, $attendances, $overtimes, $period, $periodName)
    {
        $net_salary         = 0;
        $deductions         = 0;
        $allowances         = 0;
        $overtimes_bonus    = 0;

        $employee_id    = $employee->id;
        $base_salary    = [
            'daily'             => $employee->salaryDetails->daily_base_salary,
            'monthly'           => $employee->salaryDetails->monthly_base_salary,
            'allowance'         => $employee->salaryDetails->allowance,
            'meal_allowance'    => $employee->salaryDetails->meal_allowance,
            'bonus'             => $employee->salaryDetails->bonus,
            'overtime'          => $employee->salaryDetails->overtime,
        ];

        $max_days           = 26;

        $lateMinutes        = 0;
        $overtimeMinutes    = 0;
        $totalData          = 0;
        $totalAttendances   = 0;
        $absentDays         = 0;
        $halfDays           = 0;
        $permissionDays     = 0;
        $sickDays           = 0;
        $leaveDays          = 0;
        $offDays            = 0;

        foreach ($attendances as $attendance) {
            $totalData++;
            $lateMinutes    += $attendance->late_arrival_time;
            $statusAbsensi  = $attendance->attendance_status_id;
            if ($statusAbsensi == 1) {
                if ($attendance->half_day) {
                    $halfDays++;
                } else {
                    $totalAttendances++;
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

        foreach ($overtimes as $overtime) {
            $start              = Carbon::parse($overtime->start_time);
            $end                = Carbon::parse($overtime->end_time);
            $diffMinutes        = $start->diffInMinutes($end);
            $overtimeMinutes    += $diffMinutes;
        }

        $deductions         += $this->calculateAbsentMonthly($base_salary, $absentDays);
        $deductions         += $this->calculateHalfDay($base_salary, $halfDays);
        $deductions         += $this->calculatePermissionMonthly($base_salary, $permissionDays);
        $deductions         += $this->calculateSickMonthly($base_salary, $sickDays);
        $deductions         += $this->calculateLeaveMonthly($base_salary, $leaveDays);
        $deductions         += $this->calculateDayOffMonthly($base_salary, $offDays);
        $deductions         += $this->calculateLate($lateMinutes);

        $allowances         += $this->calculateAllowanceMonthly($base_salary, $totalData == $max_days ? $totalData : $max_days);

        $overtimes_bonus    += $this->calculateOvertime($base_salary, $overtimeMinutes);

        $net_salary         = $base_salary['monthly'] - $deductions + $allowances + $overtimes_bonus;

        $payroll = [
            'employee_id'               => $employee_id,
            'period_name'               => $periodName,
            'period_start'              => $period['start'],
            'period_end'                => $period['end'],
            'salary_type'               => 'monthly',
            'base_salary'               => $base_salary['monthly'],
            'days'                      => $totalData,
            'present_days'              => $totalAttendances,
            'half_days'                 => $halfDays,
            'absent_days'               => $absentDays,
            'sick_days'                 => $sickDays,
            'leave_days'                => $leaveDays,
            'permission_days'           => $permissionDays,
            'off_days'                  => $offDays,
            'overtime_minutes'          => $overtimeMinutes,
            'late_minutes'              => $lateMinutes,
            'deductions'                => $deductions,
            'allowances'                => $allowances,
            'overtime'                  => $overtimes_bonus,
            'net_salary'                => $net_salary,
            'generated_at'              => now(),
        ];
        Payroll::create($payroll);
    }

    private function calculateDailyPayroll($employee, $attendances, $overtimes, $period, $periodName)
    {
        $net_salary         = 0;
        $total_base_salary  = 0;
        $deductions         = 0;
        $allowances         = 0;
        $overtimes_bonus    = 0;

        $employee_id    = $employee->id;
        $base_salary    = [
            'daily'             => $employee->salaryDetails->daily_base_salary,
            'allowance'         => $employee->salaryDetails->allowance,
            'meal_allowance'    => $employee->salaryDetails->meal_allowance,
            'bonus'             => $employee->salaryDetails->bonus,
            'overtime'          => $employee->salaryDetails->overtime,
        ];

        $lateMinutes        = 0;
        $overtimeMinutes    = 0;
        $totalData          = 0;
        $totalAttendances   = 0;
        $absentDays         = 0;
        $halfDays           = 0;
        $permissionDays     = 0;
        $sickDays           = 0;
        $leaveDays          = 0;
        $offDays            = 0;

        foreach ($attendances as $attendance) {
            $totalData++;
            $lateMinutes    += $attendance->late_arrival_time;
            $statusAbsensi  = $attendance->attendance_status_id;
            if ($statusAbsensi == 1) {
                if ($attendance->half_day) {
                    $halfDays++;
                } else {
                    $totalAttendances++;
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

        foreach ($overtimes as $overtime) {
            $start              = Carbon::parse($overtime->start_time);
            $end                = Carbon::parse($overtime->end_time);
            $diffMinutes        = $start->diffInMinutes($end);
            $overtimeMinutes    += $diffMinutes;
        }

        $total_base_salary  += $this->calculateBaseSalaryDaily($base_salary, $totalData);

        $deductions         += $this->calculateAbsentMonthly($base_salary, $absentDays);
        $deductions         += $this->calculateHalfDay($base_salary, $halfDays);
        $deductions         += $this->calculatePermissionMonthly($base_salary, $permissionDays);
        $deductions         += $this->calculateLeaveMonthly($base_salary, $leaveDays);
        $deductions         += $this->calculateDayOffMonthly($base_salary, $offDays);
        $deductions         += $this->calculateLate($lateMinutes);

        $allowances         += $this->calculateAllowanceDaily($base_salary, $totalData);

        $overtimes_bonus    += $this->calculateOvertime($base_salary, $overtimeMinutes);

        $net_salary = $total_base_salary - $deductions + $allowances + $overtimes_bonus;

        $payroll = [
            'employee_id'               => $employee_id,
            'period_name'               => $periodName,
            'period_start'              => $period['start'],
            'period_end'                => $period['end'],
            'salary_type'               => 'daily',
            'base_salary'               => $total_base_salary, // checked
            'days'                      => $totalData,
            'present_days'              => $totalAttendances,
            'half_days'                 => $halfDays,
            'absent_days'               => $absentDays,
            'sick_days'                 => $sickDays,
            'leave_days'                => $leaveDays,
            'permission_days'           => $permissionDays,
            'off_days'                  => $offDays,
            'overtime_minutes'          => $overtimeMinutes,
            'late_minutes'              => $lateMinutes,
            'deductions'                => $deductions,
            'allowances'                => $allowances,
            'overtime'                  => $overtimes_bonus,
            'net_salary'                => $net_salary,
            'generated_at'              => now(),
        ];
        Payroll::create($payroll);
    }

    private function calculateBaseSalaryDaily($base_salary, $totalDays)
    {
        return $base_salary['daily'] * $totalDays;
    }

    private function calculateAllowanceDaily($base_salary, $totalDays)
    {
        return ($base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    private function calculateAbsentMonthly($base_salary, $totalDays)
    {
        return ($base_salary['daily'] + $base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    private function calculateHalfDay($base_salary, $totalDays)
    {
        return (int) (($base_salary['daily'] / 2) * $totalDays);
    }

    private function calculatePermissionMonthly($base_salary, $totalDays)
    {
        return ($base_salary['daily'] + $base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    private function calculateSickMonthly($base_salary, $totalDays)
    {
        return ($base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    private function calculateLeaveMonthly($base_salary, $totalDays)
    {
        return ($base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    private function calculateDayOffMonthly($base_salary, $totalDays)
    {
        return ($base_salary['daily'] + $base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    private function calculateLate($totalMinutes)
    {
        $decrement = 1000;
        return $totalMinutes * $decrement;
    }

    private function calculateOvertime($overtime, $totalDays)
    {
        // return (int) ($overtime['overtime'] * $totalDays);
        return (int) (1000 * $totalDays);
    }

    private function calculateAllowanceMonthly($base_salary, $totalDays)
    {
        return ($base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }
}
