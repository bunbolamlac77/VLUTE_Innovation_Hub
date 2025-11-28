<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Idea;

class MentorController extends Controller
{
    /**
     * Hiển thị danh sách các ý tưởng mà user đang làm Mentor.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Chỉ cho phép giảng viên truy cập (role = staff)
        if (!$user || !$user->hasRole('staff')) {
            abort(403);
        }

        // Lấy ý tưởng mà user có trong bảng members với role là 'mentor'
        $mentoredIdeas = Idea::whereHas('members', function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->where('role_in_team', 'mentor');
        })
            ->with(['owner', 'faculty', 'category']) // Eager load để tối ưu
            ->latest()
            ->paginate(10);

        return view('mentor.index', compact('mentoredIdeas'));
    }
}

