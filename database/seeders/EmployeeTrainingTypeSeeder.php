<?php

namespace Database\Seeders;

use App\Models\EmployeeTrainingType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeTrainingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Pre-Training',
            'Training',
            'Re-Training',
        ];

        foreach ($data as $item) {
            EmployeeTrainingType::create([
                'name' => $item,
            ]);
        }
    }
}
