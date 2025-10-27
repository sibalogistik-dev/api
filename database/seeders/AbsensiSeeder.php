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
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subMonth()->startOfMonth()->addDays(27 + $i);
                if ($date->dayOfWeek == 0) {
                    continue;
                }
                $branchStartTime    = now()->parse($cabang->start_time);
                $startTime          = '08:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
                $employeeStartTime  = now()->parse($startTime);
                $endTime            = '17:' . str_pad(rand(30, 59), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);

                $st             = Carbon::parse($employeeStartTime);
                $brst           = Carbon::parse($branchStartTime);
                $lateArrival    = $st->isAfter($brst) ? abs((int) $st->diffInMinutes($brst)) : 0;

                Absensi::create([
                    'employee_id'           => $dataAbsen[$j],
                    'attendance_status_id'  => rand(1, 2),
                    // 'attendance_status_id'  => 1,
                    'date'                  => $date->format('Y-m-d'),
                    'start_time'            => $startTime,
                    'end_time'              => $endTime,
                    'attendance_image'      => 'uploads/attendance_image/default.webp',
                    'description'           => 'Deskripsi Absensi ' . $kry->name,
                    'latitude'              => $cabang->latitude,
                    'longitude'             => $cabang->longitude,
                    'late_arrival_time'     => $lateArrival,
                ]);
            }
        }
    }
}
