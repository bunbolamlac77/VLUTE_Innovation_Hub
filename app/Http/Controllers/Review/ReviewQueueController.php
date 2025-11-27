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

        // Xây dựng query theo vai trò
        $query = Idea::query()->with(['owner', 'faculty', 'category', 'members.user']);

        if ($user->hasRole('center') && !$user->hasRole('board')) {
            // Trung tâm: mặc định chỉ xem hàng chờ submitted_center
            $query->where('status', 'submitted_center');
        } elseif ($user->hasRole('board') && !$user->hasRole('center')) {
            // BGH: xem các hồ sơ đã được TT duyệt (approved_center) hoặc cần sửa ở cấp BGH
            $query->whereIn('status', ['approved_center', 'submitted_board', 'needs_change_board']);
        } else {
            // Reviewer generic: xem các trạng thái cấp trên (an toàn)
            $query->whereIn('status', ['submitted_center', 'needs_change_center', 'approved_center', 'needs_change_board']);
        }

        // Lọc theo trạng thái nếu có chọn
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ideas = $query->orderBy('updated_at', 'asc')->paginate(20)->withQueryString();

        return view('manage.review-queue.index', [
            'ideas' => $ideas,
        ]);
    }
}
