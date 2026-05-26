<?php

namespace Database\Seeders;

use Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userAdmin = [
            ['name' => 'admin', 'email' => 'admin@admin.com', 'password' => Hash::make('69241851'), 'role' => 'admin'],
        ];

        foreach ($userAdmin as $user) {
            User::create($user);
        }
    }
}
