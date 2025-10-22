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
     * Hành vi: đánh dấu verified -> đăng xuất (nếu có) -> về trang login với status.
     */
    public function __invoke(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // So khớp hash theo chuẩn Laravel
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')
                ->with('status', 'Link xác thực không hợp lệ hoặc đã hết hạn.');
        }

        if (!$user->hasVerifiedEmail()) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        }

        // Đảm bảo không auto-login sau khi verify
        if (Auth::check()) {
            Auth::logout();
        }

        return redirect()->route('login')->with('status', 'Xác thực email thành công. Vui lòng đăng nhập.');
    }
}