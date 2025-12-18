<?php

namespace App\Http\Controllers\Review;

use App\Notifications\IdeaStatusChanged;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\Review;
use App\Models\ChangeRequest;
use App\Models\ReviewAssignment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ReviewFormController extends Controller
{
    /**
     * Hiển thị form phản biện cho một ý tưởng.
     */
    public function show(Idea $idea): View
    {
        // Kiểm tra GV có quyền review ý tưởng này không
        $this->authorize('review', $idea);

        // Load tất cả thông tin chi tiết của ý tưởng
        $idea->load([
            'owner',
            'faculty',
            'category',
            'members.user',
            'attachments.uploader',
            // Load các đánh giá/yêu cầu cũ để hiển thị lịch sử
            'reviews.assignment.reviewer',
            'reviews.changeRequests',
            'changeRequests.review',
            'auditLogs.actor',
        ]);

        return view('manage.review-form.show', [
            'idea' => $idea,
        ]);
    }

    /**
     * Lưu kết quả phản biện (GV duyệt hoặc yêu cầu sửa)
     */
    public function store(Request $request, Idea $idea): RedirectResponse
    {
        // Bảo vệ hàm này bằng Policy
        $this->authorize('review', $idea);

        $user = Auth::user(); // Lấy thông tin GV đang phản biện

        // 1. Validate dữ liệu
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:approve,request_changes'],
            'comment' => ['nullable', 'string', 'max:5000'],
        ]);

        // Bắt buộc phải có comment nếu "Yêu cầu Chỉnh sửa"
        if ($validated['action'] === 'request_changes' && empty($validated['comment'])) {
            return back()->withErrors(['comment' => 'Vui lòng nhập nội dung yêu cầu chỉnh sửa.'])->withInput();
        }

        // Xác định review_level dựa trên role của user
        // Mặc định: phản biện ở cấp Trung tâm (không còn cấp GV)
        $reviewLevel = 'center';
        if ($user->hasRole('board')) {
            $reviewLevel = 'board';
        }

        // Sử dụng transaction để đảm bảo tính nhất quán
        DB::beginTransaction();
        try {
            // 2. Tìm hoặc tạo ReviewAssignment cho idea này và reviewer hiện tại
            $assignment = ReviewAssignment::firstOrCreate(
                [
                    'idea_id' => $idea?->id,
                    'reviewer_id' => $user?->id,
                    'review_level' => $reviewLevel,
                ],
                [
                    'assigned_by' => $user?->id, // Tự phân công hoặc có thể để null
                    'status' => 'pending',
                ]
            );

            // 3. Tạo bản ghi Review (để lưu lịch sử)
            $review = Review::create([
                'assignment_id' => $assignment?->id,
                'overall_comment' => $validated['comment'] ?? null,
                'decision' => $validated['action'] === 'approve' ? 'approved' : 'needs_change',
            ]);

            // 4. Xử lý hành động
            if ($validated['action'] === 'approve') {
                // Nếu Duyệt: Cập nhật status của Idea để chuyển lên cấp tiếp theo
                if ($reviewLevel === 'center') {
                    // Trung tâm duyệt: hoặc approved_center, hoặc duyệt cuối nếu không yêu cầu BGH
                    if (config('ideas.require_board_approval')) {
                        $idea?->update(['status' => 'approved_center']);
                    } else {
                        $idea?->update(['status' => 'approved_final']);
                    }
                } elseif ($reviewLevel === 'board') {
                    $idea?->update(['status' => 'approved_final']);
                } else {
                    // Fallback an toàn: coi như cấp Trung tâm
                    if (config('ideas.require_board_approval')) {
                        $idea?->update(['status' => 'approved_center']);
                    } else {
                        $idea?->update(['status' => 'approved_final']);
                    }
                }

                // Đánh dấu assignment là completed
                $assignment?->markAsCompleted();

                // (Tùy chọn: Gửi email cho SV báo đã được duyệt)

            } else { // ($validated['action'] === 'request_changes')

                // Nếu Yêu cầu sửa: Tạo bản ghi ChangeRequest
                ChangeRequest::create([
                    'review_id' => $review?->id, // Liên kết với review vừa tạo
                    'idea_id' => $idea?->id,
                    'request_message' => $validated['comment'],
                    'is_resolved' => false,
                ]);

                // Cập nhật status của Idea để trả về cho SV
                if ($reviewLevel === 'center') {
                    $idea?->update(['status' => 'needs_change_center']);
                } elseif ($reviewLevel === 'board') {
                    $idea?->update(['status' => 'needs_change_board']);
                } else {
                    // Fallback: treat as center level in case of legacy data
                    $idea?->update(['status' => 'needs_change_center']);
                }

                // Đánh dấu assignment là completed
                $assignment?->markAsCompleted();

                // (Tùy chọn: Gửi email cho SV báo cần chỉnh sửa)
            }

            DB::commit();

            // Gửi thông báo cho chủ sở hữu ý tưởng
            try {
                $notificationStatus = $validated['action'] === 'approve' ? 'approved' : 'needs_change';
                if ($idea?->owner) {
                    $idea->owner->notify(new IdeaStatusChanged($idea, $notificationStatus));
                }
            } catch (\Throwable $e) {
                // Không để lỗi notify làm hỏng luồng chính
            }

            // 5. Chuyển hướng về hàng chờ
            return redirect()->route('manage.review-queue.index')
                ->with('success', 'Đã xử lý phản biện thành công!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Có lỗi xảy ra khi xử lý phản biện: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
