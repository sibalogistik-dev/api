<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\Karyawan;
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
                if ($employee->salaryDetails->salary_type == 'monthly') {
                    $this->calculateMonthlyPayroll($employee, $attendances, $period, $periodName);
                } else {
                    $this->calculateDailyPayroll($employee, $attendances, $period, $periodName);
                }
            }
        });

        $this->info('Payroll generation complete.');
    }

    private function calculateMonthlyPayroll($employee, $attendances, $period, $periodName)
    {
        $net_salary     = 0;
        $deductions     = 0;
        $allowances     = 0;

        $employee_id    = $employee->id;
        $base_salary    = [
            'daily'             => $employee->salaryDetails->daily_base_salary,
            'monthly'           => $employee->salaryDetails->monthly_base_salary,
            'allowance'         => $employee->salaryDetails->allowance,
            'meal_allowance'    => $employee->salaryDetails->meal_allowance,
            'bonus'             => $employee->salaryDetails->bonus,
            'overtime'          => $employee->salaryDetails->overtime,
        ];

        $lateMinutes        = 0;
        $overtimeHours      = 0;
        $totalAttendances   = 0;
        $absentDays         = 0;
        $halfDays           = 0;
        $permissionDays     = 0;
        $sickDays           = 0;
        $leaveDays          = 0;
        $offDays            = 0;

        foreach ($attendances as $attendance) {
            $lateMinutes    += $attendance->late_arrival_time;
            $statusAbsensi  = $attendance->attendance_status_id;
            if ($statusAbsensi == 1) {
                $totalAttendances++;
                continue;
            }
            if ($statusAbsensi == 2) {
                $permissionDays++;
                continue;
            }
            if ($attendance->sick_note && $attendance->attendance_status_id == 3) {
                $sickDays++;
            } else {
                $absentDays++;
            }
            if ($statusAbsensi == 4) {
                $absentDays++;
                continue;
            }
            if ($statusAbsensi == 5) {
                $leaveDays++;
                continue;
            }
            if ($statusAbsensi == 6) {
                $offDays++;
                continue;
            }
            if ($attendance->half_day) {
                $halfDays++;
            }
        }

        $deductions     += $this->calculateAbsent($base_salary, $absentDays);
        $deductions     += $this->calculateHalfDay($base_salary, $halfDays);
        $deductions     += $this->calculatePermission($base_salary, $permissionDays);
        $deductions     += $this->calculateSick($base_salary, $permissionDays);
        $deductions     += $this->calculateLeave($base_salary, $permissionDays);
        $deductions     += $this->calculateDayOff($base_salary, $permissionDays);
        $deductions     += $this->calculateLate($lateMinutes);

        $allowances     += $this->calculateAllowance($base_salary, $totalAttendances);
        $allowances     += $this->calculateOvertime($base_salary, $totalAttendances);

        $net_salary     = $base_salary['monthly'] - $deductions + $allowances;

        $payroll = [
            'employee_id'           => $employee_id,
            'period_name'           => $periodName,
            'period_start'          => $period['start'],
            'period_end'            => $period['end'],
            'total_present_days'    => $totalAttendances,
            'total_absent_days'     => $absentDays,
            'total_sick_days'       => $sickDays,
            'total_leave_days'      => $leaveDays,
            'total_permission_days' => $permissionDays,
            'total_off_days'        => $offDays,
            'total_late_minutes'    => $lateMinutes,
            'overtime_hours'        => $overtimeHours,
            'monthly_base_salary'   => $base_salary['monthly'],
            'deductions'            => $deductions,
            'allowances'            => $allowances,
            'net_salary'            => $net_salary,
            'generated_at'          => now(),
        ];
        Payroll::create($payroll);
    }

    private function calculateDailyPayroll($employee, $attendances, $period, $periodName)
    {
        // 
    }

    // tanpa keterangan
    private function calculateAbsent($base_salary, $totalDays, $type = 'monthly')
    {
        return ($base_salary['daily'] + $base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    // setengah hari
    private function calculateHalfDay($base_salary, $totalDays)
    {
        return (int) (($base_salary['daily'] / 2) * $totalDays);
    }

    // izin
    private function calculatePermission($base_salary, $totalDays)
    {
        return ($base_salary['daily'] + $base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    // sakit
    private function calculateSick($base_salary, $totalDays)
    {
        return ($base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    // cuti
    private function calculateLeave($base_salary, $totalDays)
    {
        return ($base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    // libur
    private function calculateDayOff($base_salary, $totalDays)
    {
        return ($base_salary['daily'] + $base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }

    // terlambat
    private function calculateLate($totalMinutes)
    {
        $decrement = 1000;
        return $totalMinutes * $decrement;
    }

    // jam lembur
    private function calculateOvertime($overtime, $totalDays)
    {
        return 0;
    }

    private function calculateAllowance($base_salary, $totalDays)
    {
        return ($base_salary['allowance'] + $base_salary['meal_allowance'] + $base_salary['bonus']) * $totalDays;
    }
}
