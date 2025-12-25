<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicVerifyEmailController extends Controller
{
    /**
     * Xác thực email bằng link ký (không yêu cầu đang đăng nhập).
     * Hành vi: đánh dấu verified -> giữ đăng nhập nếu user đã đăng nhập -> redirect phù hợp.
     */
    public function __invoke(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // So khớp hash theo chuẩn Laravel
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')
                ->with('status', 'Link xác thực không hợp lệ hoặc đã hết hạn.');
        }

        $wasAlreadyVerified = $user->hasVerifiedEmail();
        $wasLoggedIn = Auth::check() && Auth::id() === $user->id;

        if (!$wasAlreadyVerified) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        }

        // Nếu user đã đăng nhập và đã verify, giữ đăng nhập và redirect về dashboard
        if ($wasLoggedIn) {
            return redirect()->route('dashboard')
                ->with('status', 'Xác thực email thành công!');
        }

        // Nếu user chưa đăng nhập, redirect về login với thông báo
        return redirect()->route('login')
            ->with('status', 'Xác thực email thành công. Vui lòng đăng nhập.');
    }
}