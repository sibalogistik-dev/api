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
        // $dataAbsen = Karyawan::pluck('id')->toArray();
        // unset($dataAbsen[29]);
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

                $attendance_status  = rand(0, 100) <= 95 ? 1 : rand(2, 6);
                $half_day           = $attendance_status === 1 ? rand(1, 100) > 95 : false;
                $sick_note          = $attendance_status === 3 ? rand(1, 100) > 95 : false;;

                Absensi::create([
                    'employee_id'           => $kry->id,
                    'attendance_status_id'  => $attendance_status,
                    // 'attendance_status_id'  => 1,
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
