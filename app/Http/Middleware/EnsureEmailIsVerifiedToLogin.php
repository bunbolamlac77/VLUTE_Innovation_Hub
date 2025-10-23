<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerifiedToLogin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Chưa đăng nhập -> để middleware 'auth' xử lý
        if (!$user) {
            return redirect()->route('login');
        }

        // Đã verify thì cho qua
        if ($user->hasVerifiedEmail()) {
            return $next($request);
        }

        // Chưa verify: set flag, logout và trả về login
        $email = $user->email;
        Auth::logout();

        return redirect()
            ->route('login')
            ->with('unverified', true)
            ->with('unverified_email', $email)
            ->with('status', 'Tài khoản của bạn chưa xác thực email. Vui lòng kiểm tra hộp thư.');
    }
}