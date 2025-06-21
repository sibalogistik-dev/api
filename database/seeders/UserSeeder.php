<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'administrator@gmail.com',
            'username' => 'administrator',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'user_type' => 'direction',
        ]);
        // $user->assignRole('administrator');
        $user->givePermissionTo('hrd app');
    }
}
