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
        $pending = User::where('approval_status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.approvals.index', compact('pending'));
    }

    /**
     * Duyệt tài khoản + set role (admin có thể chọn lại role trong form).
     */
    public function approve(Request $request, User $user): RedirectResponse
    {
        $role = $request->string('role')->toString();

        // Nếu form không gửi role, gợi ý theo email
        if (!$role) {
            $role = $this->guessRoleByEmail($user->email);
        }

        if (!in_array($role, ['student', 'staff', 'enterprise', 'admin'], true)) {
            return back()->with('status', 'Role không hợp lệ.');
        }

        $user->update([
            'role' => $role,
            'approval_status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Đã duyệt: ' . $user->email . " (role: {$role})");
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