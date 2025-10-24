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
            'start_date'    => '2025-10-25',
            'end_date'      => '2025-10-25',
        ];

        RemoteAttendance::create($data);
    }
}
