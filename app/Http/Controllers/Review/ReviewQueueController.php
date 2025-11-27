<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ReviewQueueController extends Controller
{
    /**
     * Hiển thị danh sách các ý tưởng đang chờ duyệt.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Chỉ cho phép Trung tâm/BGH/Reviewer truy cập hàng chờ
        if (!$user->hasRole('center') && !$user->hasRole('board') && !$user->hasRole('reviewer')) {
            abort(403);
        }

        // Tạm thời: Lấy tất cả ý tưởng đã nộp (cấp Trung tâm trở lên)
        $query = Idea::whereIn('status', [
            'submitted_center',
            'needs_change_center',
            'submitted_board',
            'needs_change_board'
        ])
            ->with(['owner', 'faculty', 'category', 'members.user'])
            ->orderBy('updated_at', 'asc'); // Ưu tiên cái cũ

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo khoa (nếu user là GV thuộc khoa cụ thể)
        // TODO: Thêm logic lọc theo khoa của GV

        $ideas = $query->paginate(20)->withQueryString();

        return view('manage.review-queue.index', [
            'ideas' => $ideas,
        ]);
    }
}
