<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserActionController extends Controller
{
    public function updateRole(Request $request, User $user)
    {
        // Chỉ cho phép đổi vai cho staff/center/board từ domain vlute.edu.vn
        // Sinh viên (student) và doanh nghiệp (enterprise) không được đổi vai
        $domain = str($user->email)->after('@')->lower()->toString();

        // Kiểm tra user có được phép đổi vai không
        $canChangeRole = $domain === 'vlute.edu.vn' && in_array($user->role, ['staff', 'center', 'board'], true);

        if (!$canChangeRole) {
            return to_route('admin.home', ['tab' => 'users'])->with('status', 'Không thể đổi vai trò cho ' . $user->role_label . '.');
        }

        $allowed = ['staff', 'center', 'board'];
        $role = $request->string('role')->toString();

        if (!in_array($role, $allowed, true)) {
            return to_route('admin.home', ['tab' => 'users'])->with('status', 'Vai trò không hợp lệ.');
        }

        $old = $user->role;
        $user->update(['role' => $role]);

        if (class_exists(\App\Models\AuditLog::class)) {
            \App\Models\AuditLog::create([
                'action' => 'role_changed',
                'actor_id' => $request->user()->id,
                'target_id' => $user->id,
                'target_type' => User::class,
                'meta' => json_encode(['old' => $old, 'new' => $role], JSON_UNESCAPED_UNICODE),
            ]);
        }

        return to_route('admin.home', ['tab' => 'users'])->with('status', 'Đã đổi vai cho ' . $user->email);
    }

    public function lock(Request $request, User $user)
    {
        // Không cho phép khóa chính mình
        if ($user->id === $request->user()->id) {
            return to_route('admin.home', ['tab' => 'users'])->with('status', 'Bạn không thể khóa chính mình.');
        }

        // Không cho phép khóa admin khác
        if ($user->role === 'admin' && $request->user()->role !== 'admin') {
            return to_route('admin.home', ['tab' => 'users'])->with('status', 'Bạn không có quyền khóa admin.');
        }

        $user->lock();

        if (class_exists(\App\Models\AuditLog::class)) {
            \App\Models\AuditLog::create([
                'action' => 'user_locked',
                'actor_id' => $request->user()->id,
                'target_id' => $user->id,
                'target_type' => User::class,
                'meta' => json_encode(['email' => $user->email], JSON_UNESCAPED_UNICODE),
            ]);
        }

        return to_route('admin.home', ['tab' => 'users'])->with('status', 'Đã khóa tài khoản: ' . $user->email);
    }

    public function unlock(Request $request, User $user)
    {
        $user->unlock();

        if (class_exists(\App\Models\AuditLog::class)) {
            \App\Models\AuditLog::create([
                'action' => 'user_unlocked',
                'actor_id' => $request->user()->id,
                'target_id' => $user->id,
                'target_type' => User::class,
                'meta' => json_encode(['email' => $user->email], JSON_UNESCAPED_UNICODE),
            ]);
        }

        return to_route('admin.home', ['tab' => 'users'])->with('status', 'Đã mở khóa tài khoản: ' . $user->email);
    }

    public function destroy(Request $request, User $user)
    {
        // Không cho phép xóa chính mình
        if ($user->id === $request->user()->id) {
            return to_route('admin.home', ['tab' => 'users'])->with('status', 'Bạn không thể xóa chính mình.');
        }

        // Không cho phép xóa admin khác
        if ($user->role === 'admin' && $request->user()->role !== 'admin') {
            return to_route('admin.home', ['tab' => 'users'])->with('status', 'Bạn không có quyền xóa admin.');
        }

        $email = $user->email;
        $user->delete();

        if (class_exists(\App\Models\AuditLog::class)) {
            \App\Models\AuditLog::create([
                'action' => 'user_deleted',
                'actor_id' => $request->user()->id,
                'target_id' => $user->id,
                'target_type' => User::class,
                'meta' => json_encode(['email' => $email], JSON_UNESCAPED_UNICODE),
            ]);
        }

        return to_route('admin.home', ['tab' => 'users'])->with('status', 'Đã xóa tài khoản: ' . $email);
    }
}