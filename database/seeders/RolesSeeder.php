<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // slug là “mã kỹ thuật”, name là nhãn tiếng Việt
        $roles = [
            ['slug' => 'student', 'name' => 'Sinh viên'],
            ['slug' => 'staff', 'name' => 'Giảng viên'],
            ['slug' => 'center', 'name' => 'Trung tâm ĐMST'],
            ['slug' => 'board', 'name' => 'Ban giám hiệu'],
            ['slug' => 'enterprise', 'name' => 'Doanh nghiệp'],
            ['slug' => 'reviewer', 'name' => 'Người phản biện'],
            ['slug' => 'admin', 'name' => 'Quản trị'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['slug' => $r['slug']], ['name' => $r['name']]);
        }
    }
}