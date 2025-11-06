<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewFormController extends Controller
{
    /**
     * Hiển thị form phản biện cho một ý tưởng.
     */
    public function show(Idea $idea): View
    {
        // (Tùy chọn: Thêm Policy để kiểm tra GV có quyền review ý tưởng này không)
        // $this->authorize('review', $idea); 

        // Load tất cả thông tin chi tiết của ý tưởng
        $idea->load([
            'owner',
            'faculty',
            'category',
            'members.user',
            'attachments.uploader',
            'reviewAssignments' => function ($query) {
                $query->with(['reviewer', 'review.changeRequests']);
            },
        ]);

        return view('manage.review-form.show', [
            'idea' => $idea,
        ]);
    }

    /**
     * Lưu kết quả phản biện (Bước 5)
     */
    public function store(Request $request, Idea $idea)
    {
        // Chúng ta sẽ làm bước này sau
        return redirect()->back();
    }
}
