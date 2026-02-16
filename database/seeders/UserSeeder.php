<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'dhaker',
            'email' => 'dhaker.bouzid300@gmail.com',
            'password' => Hash::make('Zeherbo30?'),
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ]);

        User::factory(10)->create();
    }
}
