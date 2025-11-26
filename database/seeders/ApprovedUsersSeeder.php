<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ApprovedUsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Đảm bảo các role tồn tại
            foreach (['student','staff','center','board','enterprise'] as $slug) {
                Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug)]);
            }

            // Đảm bảo có nhiều khoa để gán cho Giảng viên
            $faculties = [
                ['code' => 'CNTT', 'name' => 'Khoa Công nghệ thông tin', 'description' => 'CNTT', 'sort_order' => 1],
                ['code' => 'DDT',  'name' => 'Khoa Điện - Điện tử',      'description' => 'Điện - Điện tử', 'sort_order' => 2],
                ['code' => 'CKD',  'name' => 'Khoa Cơ khí - Động lực',    'description' => 'Cơ khí - Động lực', 'sort_order' => 3],
                ['code' => 'KT',   'name' => 'Khoa Kinh tế',              'description' => 'Kinh tế', 'sort_order' => 4],
                ['code' => 'NN',   'name' => 'Khoa Ngoại ngữ',            'description' => 'Ngoại ngữ', 'sort_order' => 5],
            ];
            foreach ($faculties as $f) {
                Faculty::firstOrCreate(['code' => $f['code']], $f);
            }

            $facultyCNTT = Faculty::where('code', 'CNTT')->first();
            $facultyDDT  = Faculty::where('code', 'DDT')->first();
            $facultyCKD  = Faculty::where('code', 'CKD')->first();
            $facultyKT   = Faculty::where('code', 'KT')->first();
            $facultyNN   = Faculty::where('code', 'NN')->first();

            // Mật khẩu mặc định cho tất cả tài khoản seed này
            $defaultPassword = 'Password@123';

            // 1) Student (Sinh viên) - đã duyệt sẵn
            $student = User::firstOrCreate(
                ['email' => 'student1@st.vlute.edu.vn'],
                [
                    'name' => 'Student One',
                    'password' => Hash::make($defaultPassword),
                    'role' => 'student',
                    'approval_status' => 'approved',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $student->syncRoles(['student']);

            // 2-6) 5 tài khoản Giảng viên theo 5 khoa khác nhau (đã duyệt sẵn)
            $lecturers = [
                ['email' => 'gv.cntt@vlute.edu.vn', 'name' => 'GV CNTT', 'faculty' => $facultyCNTT],
                ['email' => 'gv.ddt@vlute.edu.vn',  'name' => 'GV DDT',  'faculty' => $facultyDDT],
                ['email' => 'gv.ckd@vlute.edu.vn',  'name' => 'GV CKD',  'faculty' => $facultyCKD],
                ['email' => 'gv.kt@vlute.edu.vn',   'name' => 'GV KT',   'faculty' => $facultyKT],
                ['email' => 'gv.nn@vlute.edu.vn',   'name' => 'GV NN',   'faculty' => $facultyNN],
            ];

            foreach ($lecturers as $l) {
                $lecturer = User::firstOrCreate(
                    ['email' => $l['email']],
                    [
                        'name' => $l['name'],
                        'password' => Hash::make($defaultPassword),
                        'role' => 'staff',
                        'approval_status' => 'approved',
                        'is_active' => true,
                        'email_verified_at' => now(),
                    ]
                );
                $lecturer->syncRoles(['staff']);

                Profile::updateOrCreate(
                    ['user_id' => $lecturer->id],
                    ['faculty_id' => optional($l['faculty'])->id]
                );
            }

            // 7) Center (Trung tâm ĐMST)
            $center = User::firstOrCreate(
                ['email' => 'center@vlute.edu.vn'],
                [
                    'name' => 'Trung tâm ĐMST',
                    'password' => Hash::make($defaultPassword),
                    'role' => 'center',
                    'approval_status' => 'approved',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $center->syncRoles(['center']);

            // 8) Board (Ban giám hiệu)
            $board = User::firstOrCreate(
                ['email' => 'board@vlute.edu.vn'],
                [
                    'name' => 'Ban giám hiệu',
                    'password' => Hash::make($defaultPassword),
                    'role' => 'board',
                    'approval_status' => 'approved',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $board->syncRoles(['board']);

            // 9) Enterprise (Doanh nghiệp)
            $enterprise = User::firstOrCreate(
                ['email' => 'hr@acme.example'],
                [
                    'name' => 'ACME Corp',
                    'password' => Hash::make($defaultPassword),
                    'role' => 'enterprise',
                    'approval_status' => 'approved',
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'company' => 'ACME Corp',
                    'position' => 'HR Manager',
                    'interest' => 'it',
                ]
            );
            $enterprise->syncRoles(['enterprise']);
        });
    }
}
