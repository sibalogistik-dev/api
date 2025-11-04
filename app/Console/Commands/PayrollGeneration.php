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
    protected $signature = 'app:payroll-generation {month? : Format YYYY-MM (default: current month)}';
    protected $description = 'Generate payroll for all employees';

    public function handle()
    {
        $month          = $this->argument('month') ?? now()->format('Y-m');
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

        DB::transaction(function () use ($period, $periodName, $monthsDays) {
            $employees  = Karyawan::all();

            foreach ($employees as $employee) {
                $attendances    = Absensi::where('employee_id', $employee->id)
                    ->whereBetween('date', [$period['start'], $period['end']])
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
        $maxDays        = 26;
        $lateMinutes    = 0;
        $totalData      = $attendances->count();
        $overtimePay    = $this->calculateOvertimePay($overtime, $base_salary['overtime']);


        $this->info("Total data absensi {$totalData} / {$periodDays} untuk karyawan {$employee->name}");
    }

    private function calculateDailyPayroll($employee, $attendances, $overtime, $period, $periodName)
    {
        $base_salary    = [
            'daily'             => $employee->salaryDetails->daily_base_salary,
            'monthly'           => $employee->salaryDetails->monthly_base_salary,
            'allowance'         => $employee->salaryDetails->allowance,
            'meal_allowance'    => $employee->salaryDetails->meal_allowance,
            'bonus'             => $employee->salaryDetails->bonus,
            'overtime'          => $employee->salaryDetails->overtime,
        ];
        $overtimePay = $this->calculateOvertimePay($overtime, $base_salary['overtime']);
        $lateMinutes    = 0;
        $totalData      = $attendances->count();
        $overtimePay    = $this->calculateOvertimePay($overtime, $base_salary['overtime']);


        $this->info("Total data absensi {$totalData} for employee {$employee->name}");
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

    private function calculateOvertimePay($overtime, $minutelyRate)
    {
        $totalOvertimePay = 0;
        $overtimeMinutes    = 0;
        foreach ($overtime as $ot) {
            $start              = Carbon::parse($ot->start_time);
            $end                = Carbon::parse($ot->end_time);
            $diffMinutes        = $start->diffInMinutes($end);
            $overtimeMinutes    += $diffMinutes;
        }
        $totalOvertimePay = (int)($overtimeMinutes * $minutelyRate);
        return "Pay: Rp " . number_format($totalOvertimePay, 0, ',', '.');
    }
}
