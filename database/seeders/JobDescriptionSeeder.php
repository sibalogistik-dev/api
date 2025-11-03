<?php

namespace Database\Seeders;

use App\Models\JobDescription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'job_title_id'      => 1,
                'task_name'         => 'Prepare Monthly Report',
                'task_detail'       => 'Compile and analyze data for the monthly financial report.',
                'priority_level'    => 4,
            ],
            [
                'job_title_id'      => 2,
                'task_name'         => 'Client Meeting',
                'task_detail'       => 'Meet with clients to discuss project requirements and updates.',
                'priority_level'    => 3,
            ],
            [
                'job_title_id'      => 3,
                'task_name'         => 'Code Review',
                'task_detail'       => 'Review code submissions from team members for quality assurance.',
                'priority_level'    => 2,
            ],
        ];

        foreach ($data as $item) {
            JobDescription::create($item);
        }
    }
}
