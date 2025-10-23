<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        // Lấy user pending (chờ duyệt) mới nhất trước
        $pending = User::where('approval_status', 'pending')
            ->orderByDesc('created_at')
            ->get()
            ->map(function (User $u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                    'suggested' => $this->guessRoleByEmail($u->email), // gợi ý role theo email
                    'company' => $u->company,
                    'created_at' => $u->created_at,
                ];
            });

        return view('admin.approvals.index', compact('pending'));
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        // Lấy role từ form (nếu có), nếu không dùng role hiện tại hoặc gợi ý theo email
        $role = $request->string('role')->toString() ?: $this->guessRoleByEmail($user->email);
        if (!in_array($role, ['student', 'staff', 'enterprise', 'admin'], true)) {
            return back()->with('status', 'Role không hợp lệ.');
        }

        $user->update([
            'role' => $role,
            'approval_status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Đã duyệt: ' . $user->email . ' (role: ' . $role . ')');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        $user->update([
            'approval_status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Đã từ chối: ' . $user->email);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'in:student,staff,enterprise,admin'],
        ]);

        $user->update(['role' => $request->string('role')->toString()]);
        return back()->with('status', 'Đã cập nhật role cho: ' . $user->email);
    }

    private function guessRoleByEmail(string $email): string
    {
        $email = strtolower($email);
        $domain = str($email)->after('@')->toString();

        if ($domain === 'st.vlute.edu.vn') {
            return 'student';
        } elseif ($domain === 'vlute.edu.vn') {
            return 'staff';
        }
        return 'enterprise';
    }
}