<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@vlute.edu.vn');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'System Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@123')),
                'email_verified_at' => now(),
                'role' => 'admin',     // vai chính để middleware cũ hoạt động
                'approval_status' => 'approved',
            ]
        );

        // Gán vào pivot
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole && !$user->roles()->where('role_id', $adminRole->id)->exists()) {
            $user->roles()->attach($adminRole->id, ['assigned_by' => $user->id]);
        }
    }
}