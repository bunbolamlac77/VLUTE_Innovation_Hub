<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->string('tab')->toString() ?: 'approvals';

        // --- Approvals data ---
        $pending = collect();
        if ($tab === 'approvals') {
            $pending = User::where('approval_status', 'pending')
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($u) {
                    $domain = str($u->email)->after('@')->lower()->toString();
                    $suggested = $domain === 'vlute.edu.vn'
                        ? ['staff', 'center', 'board']  // 3 lựa chọn cho domain vlute.edu.vn
                        : ['enterprise'];     // còn lại là DN
                    return ['model' => $u, 'suggested' => $suggested];
                });
        }

        // --- Users data ---
        $users = collect();
        $filters = [];
        if ($tab === 'users') {
            $q = $request->string('q')->toString();
            $role = $request->string('role')->toString();
            $status = $request->string('status')->toString();
            $items = User::query();
            if ($q)
                $items->where(fn($w) => $w->where('email', 'like', "%$q%")->orWhere('name', 'like', "%$q%"));
            if ($role)
                $items->where('role', $role);
            if ($status === 'pending')
                $items->where('approval_status', 'pending');
            if ($status === 'approved')
                $items->where('approval_status', 'approved');
            $users = $items->latest()->paginate(12)->withQueryString();
            $filters = compact('q', 'role', 'status');
        }

        // --- Ideas (MVP): nếu chưa có bảng, cứ để mảng trống là panel vẫn hiển thị ---
        $ideas = collect();
        $ideaFilters = [];
        $reviewers = collect();
        if ($tab === 'ideas') {
            $q = $request->string('q')->toString();
            $status = $request->string('status')->toString();
            $reviewer_id = $request->string('reviewer_id')->toString();

            $query = \App\Models\Idea::with(['owner', 'reviewAssignments.reviewer']);
            if ($q) {
                $query->where('title', 'like', "%$q%");
            }
            if ($status) {
                $query->where('status', $status);
            }
            if ($reviewer_id) {
                $query->whereHas('reviewAssignments', function ($q) use ($reviewer_id) {
                    $q->where('reviewer_id', $reviewer_id);
                });
            }

            $ideas = $query->latest()->paginate(15)->withQueryString();
            $ideaFilters = compact('q', 'status', 'reviewer_id');

            // Reviewer lấy những user nội bộ:
            $reviewers = User::whereIn('role', ['staff', 'center'])->get(['id', 'name', 'email']);
        }

        // --- Taxonomies: nếu có các model này thì nạp; nếu chưa có cứ trả mảng trống ---
        $faculties = class_exists(\App\Models\Faculty::class) ? \App\Models\Faculty::orderBy('name')->get() : collect();
        $categories = class_exists(\App\Models\Category::class) ? \App\Models\Category::orderBy('name')->get() : collect();
        $tags = class_exists(\App\Models\Tag::class) ? \App\Models\Tag::orderBy('name')->get() : collect();

        // --- Logs: nếu có model AuditLog thì trả; không có thì mảng trống ---
        $logs = collect();
        $logFilters = [];
        if ($tab === 'logs' && class_exists(\App\Models\AuditLog::class)) {
            $action = $request->string('action')->toString();
            $q = $request->string('q')->toString();
            $items = \App\Models\AuditLog::query()->latest();
            if ($action)
                $items->where('action', $action);
            if ($q)
                $items->where('meta', 'like', "%$q%");
            $logs = $items->paginate(20)->withQueryString();
            $logFilters = compact('action', 'q');
        }

        return view('admin.index', compact(
            'tab',
            'pending',
            'users',
            'filters',
            'ideas',
            'ideaFilters',
            'reviewers',
            'faculties',
            'categories',
            'tags',
            'logs',
            'logFilters'
        ));
    }
}