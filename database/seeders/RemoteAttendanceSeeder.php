<?php

namespace Database\Seeders;

use App\Models\RemoteAttendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RemoteAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'employee_id'   => 1,
            'start_date'    => date('Y-m-d'),
            'end_date'      => date('Y-m-d'),
        ];

        RemoteAttendance::create($data);
    }
}
