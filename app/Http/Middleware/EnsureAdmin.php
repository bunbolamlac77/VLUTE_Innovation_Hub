<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // Kiểm tra cả field role và relationship roles
        if (!$user->hasRole('admin')) {
            abort(403, 'Bạn không có quyền truy cập trang quản trị. Vui lòng liên hệ quản trị viên để được cấp quyền.');
        }

        return $next($request);
    }
}