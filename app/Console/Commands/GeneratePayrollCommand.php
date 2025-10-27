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
        $month = $this->argument('month') ?? now()->format('Y-m');
        $periodStart = Carbon::parse("$month-28")->subMonth()->startOfDay();
        $periodEnd = Carbon::parse("$month-27")->endOfDay();

        $periodName = 'Payroll ' . $periodEnd->locale('id')->translatedFormat('F Y');
        $this->info("Generating $periodName ($periodStart → $periodEnd)");

        DB::transaction(function () use ($periodStart, $periodEnd, $periodName) {
            $employees = Karyawan::all();

            foreach ($employees as $employee) {
                $attendances = Absensi::where('employee_id', $employee->id)
                    ->whereBetween('date', [$periodStart, $periodEnd])
                    ->get();

                $totalWorkDays      = $attendances->where('attendance_status_id', 1)->count();
                $totalAbsentDays    = $attendances->whereIn('attendance_status_id', [2, 3, 4, 5, 6])->count();
                $totalLateMinutes   = $attendances->sum('late_arrival_time');

                $monthlyBase    = $employee->salaryDetails->monthly_base_salary ?? 0;
                $dailyBase      = $employee->salaryDetails->daily_base_salary ?? 0;
                $deductions     = $this->calculateDeductions($dailyBase, $totalLateMinutes, $totalAbsentDays);
                $allowances     = $this->calculateAllowances($employee->salaryDetails->bonus, $employee->salaryDetails->meal_allowance, $employee->salaryDetails->allowance);
                $netSalary      = ($monthlyBase + $allowances) - $deductions;

                Payroll::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'period_start' => $periodStart,
                        'period_end' => $periodEnd,
                    ],
                    [
                        'period_name' => $periodName,
                        'monthly_base_salary' => $monthlyBase,
                        'total_work_days' => $totalWorkDays,
                        'total_absent_days' => $totalAbsentDays,
                        'total_late_minutes' => $totalLateMinutes,
                        'deductions' => $deductions,
                        'allowances' => $allowances,
                        'net_salary' => $netSalary,
                        'generated_at' => now(),
                    ]
                );
            }
        });

        $this->info('Payroll generation complete.');
    }

    private function calculateDeductions(float $baseSalary, int $lateMinutes, int $absentDays): float
    {
        $perDay = $baseSalary;
        $absentPenalty = ((int) $perDay) * $absentDays;
        $latePenalty = $lateMinutes * 666;
        return (int) ($absentPenalty + $latePenalty);
    }

    private function calculateAllowances($employee): float
    {
        return 0;
    }
}
