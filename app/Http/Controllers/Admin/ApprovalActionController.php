<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalActionController extends Controller
{
    public function approve(Request $request, User $user)
    {
        $domain = str($user->email)->after('@')->lower()->toString();
        $allowed = $domain === 'vlute.edu.vn' ? ['staff', 'center'] : ['enterprise'];
        $role = $request->string('role')->toString();

        if (!in_array($role, $allowed, true)) {
            return to_route('admin.home', ['tab' => 'approvals'])->with('status', 'Vai trò không hợp lệ.');
        }

        $user->update([
            'role' => $role,
            'approval_status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        // (Tuỳ chọn) ghi log nếu có model AuditLog
        if (class_exists(\App\Models\AuditLog::class)) {
            \App\Models\AuditLog::create([
                'action' => 'user_approved',
                'actor_id' => $request->user()->id,
                'target_id' => $user->id,
                'target_type' => User::class,
                'meta' => json_encode(['role' => $role], JSON_UNESCAPED_UNICODE),
            ]);
        }

        return to_route('admin.home', ['tab' => 'approvals'])->with('status', 'Đã duyệt: ' . $user->email);
    }

    public function reject(Request $request, User $user)
    {
        $user->update(['approval_status' => 'rejected']);

        if (class_exists(\App\Models\AuditLog::class)) {
            \App\Models\AuditLog::create([
                'action' => 'user_rejected',
                'actor_id' => $request->user()->id,
                'target_id' => $user->id,
                'target_type' => User::class,
                'meta' => json_encode([], JSON_UNESCAPED_UNICODE),
            ]);
        }

        return to_route('admin.home', ['tab' => 'approvals'])->with('status', 'Đã từ chối: ' . $user->email);
    }
}