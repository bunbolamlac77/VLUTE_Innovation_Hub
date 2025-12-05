<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Hiển thị trang đăng ký.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản mới.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Chuẩn hóa email và tách domain để xác định validation rules
        $email = $request->string('email')->lower()->toString();
        $domain = str($email)->after('@')->toString();

        // Xác định xem có phải domain VLUTE không
        $isVluteDomain = \in_array($domain, ['vlute.edu.vn', 'st.vlute.edu.vn'], true);

        // Validation rules cơ bản
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // Nếu không phải domain VLUTE, các trường doanh nghiệp là bắt buộc
        if (!$isVluteDomain) {
            $rules['company'] = ['required', 'string', 'max:255'];
            $rules['position'] = ['required', 'string', 'max:255'];
            $rules['interest'] = ['required', 'string', 'in:it,agritech,mechanics,other'];
        } else {
            // Với domain VLUTE, các trường này là tùy chọn
            $rules['company'] = ['nullable', 'string', 'max:255'];
            $rules['position'] = ['nullable', 'string', 'max:255'];
            $rules['interest'] = ['nullable', 'string', 'in:it,agritech,mechanics,other'];
        }

        $request->validate($rules);

        // Mặc định: sinh viên (auto approved)
        $role = 'student';
        $approval = 'approved';

        // @vlute.edu.vn -> staff (cần duyệt)
        if ($domain === 'vlute.edu.vn') {
            // Mặc định staff để vào hàng chờ; Admin sẽ đổi sang Giảng viên/Trung tâm/BGH khi approve
            $role = 'staff';
            $approval = 'pending';
        } elseif ($domain !== 'st.vlute.edu.vn') {
            // Domain ngoài trường
            $role = 'enterprise';
            $approval = 'pending';
        }

        // Lấy thông tin DN (nếu có)
        $company = $request->string('company')->toString() ?: null;
        $position = $request->string('position')->toString() ?: null;
        $interest = $request->string('interest')->toString() ?: null;

        // Tạo user với role & approval định sẵn
        $user = User::create([
            'name' => $request->string('name')->toString(),
            'email' => $email,
            'password' => Hash::make($request->string('password')->toString()),
            'role' => $role,
            'approval_status' => $approval,
            'company' => $company,
            'position' => $position,
            'interest' => $interest,
        ]);
        // Sau khi $user = User::create([...]);

        // Xác định slug cho pivot (trùng với role chính đã set)
        $pivotSlug = $role; // 'student' | 'staff' | 'enterprise' (theo domain)

        try {
            $user->syncRoles([$pivotSlug]); // đưa user vào bảng role_user
        } catch (\Throwable $e) {
            // không chặn đăng ký nếu pivot gặp lỗi, chỉ log
            \Log::warning('Sync roles on register failed', ['user' => $user->id, 'err' => $e->getMessage()]);
        }

        // Gửi email xác thực (Laravel sẽ bắn notification khi event Registered được fire)
        event(new Registered($user));

        // Đăng nhập tạm để vào trang "verify-email" (chuẩn flow của Laravel/Breeze)
        Auth::login($user);

        // Chuyển tới trang nhắc xác thực email (verification.notice)
        // Lưu ý: Sau khi bấm link verify (public verify), bạn đã cấu hình để quay về /login.
        return redirect()->route('verification.notice');
    }
}