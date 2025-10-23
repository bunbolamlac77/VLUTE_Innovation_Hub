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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // nếu form có các field DN, bạn có thể thêm rules nhẹ:
            // 'company'            => ['nullable','string','max:255'],
            // 'position'           => ['nullable','string','max:255'],
            // 'interest'           => ['nullable','string','max:255'],
        ]);

        // Chuẩn hóa email và tách domain
        $email = $request->string('email')->lower()->toString();
        $domain = str($email)->after('@')->toString();

        // Mặc định: sinh viên (auto approved)
        $role = 'student';
        $approval = 'approved';

        // @vlute.edu.vn -> staff (cần duyệt)
        if ($domain === 'vlute.edu.vn') {
            $role = 'staff';
            $approval = 'pending';
        }
        // domain khác (không phải @st.vlute.edu.vn) -> enterprise (cần duyệt)
        elseif ($domain !== 'st.vlute.edu.vn') {
            $role = 'enterprise';
            $approval = 'pending';
        }

        // Lấy thông tin DN nếu có (không bắt buộc)
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

        // Gửi email xác thực (Laravel sẽ bắn notification khi event Registered được fire)
        event(new Registered($user));

        // Đăng nhập tạm để vào trang "verify-email" (chuẩn flow của Laravel/Breeze)
        Auth::login($user);

        // Chuyển tới trang nhắc xác thực email (verification.notice)
        // Lưu ý: Sau khi bấm link verify (public verify), bạn đã cấu hình để quay về /login.
        return redirect()->route('verification.notice');
    }
}