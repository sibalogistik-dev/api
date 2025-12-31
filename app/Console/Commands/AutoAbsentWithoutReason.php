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
        if (Carbon::now()->isSunday()) {
            $this->info('Hari ini hari Minggu, proses auto-absensi dilewati.');
            return Command::SUCCESS;
        }

        $today = Carbon::today()->toDateString();

        DB::transaction(function () use ($today) {
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
                    'description'          => 'Auto absent (tanpa keterangan)',
                ]);
            }
        });

        return Command::SUCCESS;
    }
}
