<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\Overtime;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OvertimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kry = Karyawan::pluck('id')->toArray();

        for ($j = 0; $j < 30; $j++) {
            for ($i = 0; $i < count($kry); $i++) {
                $overtime = rand(0, 1);
                $date = now()->subMonth()->startOfMonth()->addDays(27 + $j);
                if ($date->dayOfWeek == 0) {
                    continue;
                }
                if ($overtime) {
                    $startHour   = rand(17, 20);
                    $startMinute = ($startHour == 17) ? rand(30, 59) : rand(0, 59);
                    $startSecond = rand(0, 59);

                    $start = Carbon::createFromTime($startHour, $startMinute, $startSecond);
                    $end   = (clone $start)->addSecond(rand(1500, 10800));

                    if ($end->hour >= 24) {
                        $end->hour = 23;
                        $end->minute = 59;
                        $end->second = 59;
                    }

                    Overtime::create([
                        'employee_id'   => $kry[$i],
                        'start_time'    => $date->format('Y-m-d') . ' ' . $start->format('H:i:s'),
                        'end_time'      => $date->format('Y-m-d') . ' ' . $end->format('H:i:s'),
                        'approved'      => rand(0, 1),
                    ]);
                }
            }
        }
    }
}
