<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\ReviewAssignment;
use App\Models\User;
use App\Notifications\IdeaStatusChanged;
use Illuminate\Http\Request;

class IdeaActionController extends Controller
{
    /**
     * Update the status of an idea.
     */
    public function updateStatus(Request $request, Idea $idea)
    {
        // The view sends simple statuses, let's map them to the detailed ones if needed
        // For now, we'll just use the value directly as the MVP form suggests.
        $request->validate([
            'status' => 'required|string',
        ]);

        $old = $idea->status;
        $new = $request->status;
        $idea->update(['status' => $new]);

        // Ghi nhật ký nếu có AuditLog
        if (class_exists(\App\Models\AuditLog::class)) {
            \App\Models\AuditLog::create([
                'action' => 'idea_status_changed',
                'actor_id' => $request->user()?->id,
                'target_id' => $idea?->id,
                'target_type' => Idea::class,
                'meta' => json_encode(['old' => $old, 'new' => $new], JSON_UNESCAPED_UNICODE),
            ]);
        }

        // Gửi thông báo cho chủ ý tưởng (và có thể mở rộng cho các thành viên nếu cần)
        try {
            $notifyStatus = match (true) {
                $new === 'rejected' => 'rejected',
                str_contains($new, 'needs_change') => 'needs_change',
                str_contains($new, 'submitted') => 'submitted',
                str_contains($new, 'approved') => 'approved',
                default => 'updated',
            };
            if ($idea->owner) {
                $idea->owner->notify(new IdeaStatusChanged($idea, $notifyStatus));
            }
            // Thông báo cho các thành viên nhóm (nếu có)
            try {
                $idea->loadMissing('members.user');
                foreach ($idea->members as $member) {
                    if ($member->user && (!$idea->owner || $member->user_id !== $idea->owner->id)) {
                        $member->user->notify(new IdeaStatusChanged($idea, $notifyStatus));
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }
        } catch (\Throwable $e) {
            // Không để lỗi notify ảnh hưởng thao tác admin
        }

        return back()->with('status', 'Trạng thái ý tưởng đã được cập nhật.');
    }

    /**
     * Assign a reviewer to an idea.
     */
    public function assignReviewer(Request $request, Idea $idea)
    {
        $request->validate([
            'reviewer_id' => 'required|exists:users,id',
        ]);

        $reviewer = User::find($request->reviewer_id);

        // Ensure the selected user is a reviewer
        if (!$reviewer || !\in_array($reviewer->role, ['staff', 'center', 'board'])) {
            return back()->withErrors(['reviewer_id' => 'Người dùng được chọn không phải là reviewer.']);
        }

        // Create a new review assignment, avoiding duplicates
        ReviewAssignment::updateOrCreate(
            [
                'idea_id' => $idea?->id,
                'reviewer_id' => $reviewer?->id,
            ],
            [
                'status' => 'pending', // Set status to pending
            ]
        );

        return back()->with('status', 'Reviewer đã được gán cho ý tưởng.');
    }
}

