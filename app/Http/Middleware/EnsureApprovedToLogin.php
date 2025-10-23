<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureApprovedToLogin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Role cần duyệt mà chưa approved -> đá về login + bật modal “chưa duyệt”
        if (method_exists($user, 'needsApproval') && method_exists($user, 'isApproved')) {
            if ($user->needsApproval() && !$user->isApproved()) {
                $email = $user->email;
                Auth::logout();

                return redirect()
                    ->route('login')
                    ->with('unapproved', true)
                    ->with('unapproved_email', $email)
                    ->with('status', 'Tài khoản của bạn đang chờ phê duyệt.');
            }
        }

        return $next($request);
    }
}