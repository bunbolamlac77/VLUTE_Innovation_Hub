<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IdeaPolicy
{
    /**
     * Cho phép Admin làm mọi thứ.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Giả sử bạn có hàm hasRole() trong Model User
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    /**
     * Quyết định xem user có thể xem ý tưởng (trong trang 'My Ideas') không.
     */
    public function view(User $user, Idea $idea): bool
    {
        // 1. User là chủ sở hữu
        if ($user->id === $idea->owner_id) {
            return true;
        }

        // 2. User là thành viên trong nhóm
        return $idea->members->contains(function ($member) use ($user) {
            return $member->user_id === $user->id;
        });
    }

    /**
     * Quyết định xem user có thể cập nhật ý tưởng không.
     */
    public function update(User $user, Idea $idea): bool
    {
        // Chỉ chủ sở hữu mới được cập nhật
        if ($user->id !== $idea->owner_id) {
            return false;
        }

        // Chỉ cho phép cập nhật khi status là draft hoặc needs_change
        return $idea->isDraft() || $idea->needsChange();
    }

    /**
     * Quyết định xem user có thể xóa ý tưởng không.
     */
    public function delete(User $user, Idea $idea): bool
    {
        // Chỉ chủ sở hữu và khi ý tưởng còn là 'draft'
        return $user->id === $idea->owner_id && $idea->isDraft();
    }

    /**
     * Quyết định xem user có thể mời thành viên không.
     */
    public function invite(User $user, Idea $idea): bool
    {
        // Chủ sở hữu hoặc thành viên trong nhóm có thể mời
        if ($user->id === $idea->owner_id) {
            return true;
        }

        // Thành viên trong nhóm cũng có thể mời
        return $idea->members->contains(function ($member) use ($user) {
            return $member->user_id === $user->id;
        });
    }

    /**
     * Quyết định xem user có thể nộp ý tưởng không.
     */
    public function submit(User $user, Idea $idea): bool
    {
        // Chỉ chủ sở hữu mới được nộp
        if ($user->id !== $idea->owner_id) {
            return false;
        }

        // Chỉ cho phép nộp khi status là draft hoặc needs_change
        return $idea->isDraft() || $idea->needsChange();
    }

    /**
     * Quyết định xem user có thể duyệt ý tưởng không.
     */
    public function approve(User $user, Idea $idea): bool
    {
        // Chỉ Giảng viên, Trung tâm ĐMST, hoặc BGH
        return $user->hasRole('staff') ||
            $user->hasRole('center') ||
            $user->hasRole('board') ||
            $user->hasRole('reviewer');
    }

    /**
     * Quyết định xem user có thể phản biện ý tưởng không.
     */
    public function review(User $user, Idea $idea): bool
    {
        // 1. User phải là GV, TTDMST hoặc BGH (hoặc Admin - đã xử lý ở 'before')
        if (!$user->hasRole('staff') && !$user->hasRole('center') && !$user->hasRole('board') && !$user->hasRole('reviewer')) {
            return false;
        }

        // 2. Cho phép review nếu ý tưởng đang ở trạng thái chờ phản biện
        $reviewableStatuses = [
            'submitted_gv',
            'needs_change_gv',
            'submitted_center',
            'needs_change_center',
            'submitted_board',
            'needs_change_board'
        ];

        return in_array($idea->status, $reviewableStatuses);

        // (Nâng cao: Kiểm tra xem user này có được gán (assigned)
        // cho ý tưởng này trong bảng 'review_assignments' hay không)
    }
}
