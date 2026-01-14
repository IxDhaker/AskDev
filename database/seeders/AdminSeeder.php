<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('role', 'admin')->first();
        if ($adminUser) {
            Admin::create([
                'user_id' => $adminUser->id,
            ]);
        }
    }
}
