<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    public function run(): void {
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'administrator@gmail.com',
            'username' => 'administrator',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('administrator');
    }
}
