<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->with('roles')->paginate(20);
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRoles(Request $request, User $user): RedirectResponse
    {
        $slugs = $request->input('roles', []); // mảng slug từ checkbox
        $user->syncRoles($slugs, $request->user()->id);

        // Nếu admin muốn đổi "vai chính" (users.role), có thể chọn 1 slug làm primary_role:
        if ($request->filled('primary_role')) {
            $primary = $request->string('primary_role')->toString();
            if (in_array($primary, ['student', 'staff', 'enterprise', 'admin', 'reviewer', 'center', 'board'], true)) {
                $user->update(['role' => $primary]);
            }
        }

        return back()->with('status', 'Đã cập nhật vai cho: ' . $user->email);
    }
}