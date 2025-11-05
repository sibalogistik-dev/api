<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Cabang;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $dataAbsen = [1, 2, 3, 4, 5];

        for ($j = 0; $j < count($dataAbsen); $j++) {
            $kry = Karyawan::find($dataAbsen[$j]);
            $cabang = Cabang::find($kry->branch_id);

            $startDate = now()->subMonth()->day(28);
            $endDate = now()->day(27);

            $period = Carbon::parse($startDate)->daysUntil($endDate);

            foreach ($period as $date) {
                if ($date->isSunday()) {
                    continue;
                }

                $branchStartTime    = Carbon::parse($cabang->start_time);
                $startTime          = '08:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
                $employeeStartTime  = Carbon::parse($startTime);
                $endTime            = '17:' . str_pad(rand(30, 59), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);

                $lateArrival = $employeeStartTime->isAfter($branchStartTime)
                    ? abs((int) $employeeStartTime->diffInMinutes($branchStartTime))
                    : 0;

                $attendance_status  = rand(0, 100) <= 80 ? 1 : (rand(0, 100) <= 50 ? 3 : rand(2, 6));
                $half_day           = $attendance_status === 1 ? rand(1, 100) > 95 : false;
                $sick_note          = $attendance_status === 3 ? rand(1, 100) > 98 : false;

                Absensi::create([
                    'employee_id'           => $kry->id,
                    'attendance_status_id'  => $attendance_status,
                    'date'                  => $date->format('Y-m-d'),
                    'start_time'            => $startTime,
                    'end_time'              => $endTime,
                    'attendance_image'      => 'uploads/attendance_image/default.webp',
                    'description'           => 'Deskripsi Absensi ' . $kry->name,
                    'latitude'              => $cabang->latitude,
                    'longitude'             => $cabang->longitude,
                    'half_day'              => $half_day,
                    'sick_note'             => $sick_note,
                    'late_arrival_time'     => $half_day || $attendance_status !== 1 ? 0 : $lateArrival,
                ]);
            }
        }
    }
}
