<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Danh sách tài khoản chờ duyệt.
     */
    public function index()
    {
        $pending = \App\Models\User::where('approval_status', 'pending')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($u) {
                $email = strtolower($u->email);
                $domain = \Illuminate\Support\Str::of($email)->after('@')->toString();

                // Gợi ý role theo domain
                $suggested = match ($domain) {
                    'vlute.edu.vn' => ['staff', 'center', 'board'], // chỉ 3 lựa chọn
                    default => ['enterprise'],                // còn lại là DN
                };

                return [
                    'model' => $u,
                    'suggested' => $suggested,
                ];
            });

        return view('admin.approvals.index', compact('pending'));
    }

    public function approve(Request $request, \App\Models\User $user)
    {
        $role = $request->string('role')->toString();
        $email = strtolower($user->email);
        $domain = \Illuminate\Support\Str::of($email)->after('@')->toString();

        // Chỉ cho phép role hợp lệ theo domain
        $allowed = $domain === 'vlute.edu.vn'
            ? ['staff', 'center', 'board']
            : ['enterprise'];

        if (!in_array($role, $allowed, true)) {
            return back()->with('status', 'Vai trò không hợp lệ cho email này.');
        }

        $user->update([
            'role' => $role,
            'approval_status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', '✅ Đã duyệt tài khoản: ' . $user->email . ' (' . \App\Models\User::roleLabel($role) . ')');
    }
    /**
     * Từ chối tài khoản.
     */
    public function reject(Request $request, User $user): RedirectResponse
    {
        $user->update([
            'approval_status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Đã từ chối: ' . $user->email);
    }

    /**
     * Admin cập nhật role (không đổi approval).
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'in:student,staff,enterprise,admin'],
        ]);

        $user->update([
            'role' => $request->string('role')->toString(),
        ]);

        return back()->with('status', 'Đã cập nhật role cho: ' . $user->email);
    }

    /**
     * Gợi ý role theo domain email.
     */
    private function guessRoleByEmail(string $email): string
    {
        $email = strtolower($email);
        $domain = str($email)->after('@')->toString();

        if ($domain === 'st.vlute.edu.vn')
            return 'student';
        if ($domain === 'vlute.edu.vn')
            return 'staff';
        return 'enterprise';
    }
}