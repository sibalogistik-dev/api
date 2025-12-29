<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AutoAbsentWithoutReason extends Command
{
    protected $signature = 'attendance:auto-absent';
    protected $description = 'Auto create absent attendance for employees without attendance today';

    public function handle(): int
    {
        $today = Carbon::today()->toDateString();
        $now   = Carbon::now()->toTimeString();

        DB::transaction(function () use ($today, $now) {
            $employees = Karyawan::query()
                ->where(function ($q) use ($today) {
                    $q->whereNull('end_date')
                        ->orWhere('end_date', '>=', $today);
                })
                ->whereDoesntHave('attendance', function ($q) use ($today) {
                    $q->where('date', $today);
                })
                ->lockForUpdate()
                ->get();

            foreach ($employees as $employee) {
                Absensi::create([
                    'employee_id'          => $employee->id,
                    'attendance_status_id' => 4,
                    'date'                 => $today,
                    'start_time'           => $now,
                    'description'          => 'Auto absent (tanpa keterangan)',
                ]);
            }
        });

        return Command::SUCCESS;
    }
}
