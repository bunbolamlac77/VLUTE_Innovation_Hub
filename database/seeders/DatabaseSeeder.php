<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi seeder tạo Admin
        $this->call(RolesSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(ApprovedUsersSeeder::class);
        $this->call(FeaturedIdeasSeeder::class);

        // (Tuỳ chọn) tạo user mẫu bằng factory
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}