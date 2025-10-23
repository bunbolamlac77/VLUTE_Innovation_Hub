<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@vlute.edu.vn');

        User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'System Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@123')),
                'email_verified_at' => now(),
                'role' => 'admin',
                'approval_status' => 'approved',
            ]
        );
    }
}