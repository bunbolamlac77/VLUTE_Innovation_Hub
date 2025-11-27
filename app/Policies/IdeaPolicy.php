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
        // Chủ sở hữu luôn xem được
        if ($user->id === $idea->owner_id) {
            return true;
        }
        // Thành viên bất kỳ trong nhóm (member, owner, mentor)
        return $idea->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Quyết định xem user có thể cập nhật ý tưởng không.
     */
    public function update(User $user, Idea $idea): bool
    {
        // Chủ sở hữu có thể cập nhật khi draft hoặc cần chỉnh sửa
        if ($user->id === $idea->owner_id && ($idea->isDraft() || $idea->needsChange())) {
            return true;
        }
        // Mentor có thể chỉnh sửa nếu bật flag
        if (config('ideas.mentors_can_edit')) {
            return $idea->members()
                ->where('user_id', $user->id)
                ->where('role_in_team', 'mentor')
                ->exists();
        }
        return false;
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

        if (!($idea->isDraft() || $idea->needsChange())) {
            return false;
        }

        // Nếu bật yêu cầu mentor, bắt buộc phải có ít nhất 1 mentor
        if (config('ideas.require_mentor_to_submit')) {
            return $idea->members()->where('role_in_team', 'mentor')->exists();
        }

        return true;
    }

    /**
     * Quyết định xem user có thể duyệt ý tưởng không.
     */
    public function approve(User $user, Idea $idea): bool
    {
        // Giảng viên KHÔNG còn quyền duyệt. Chỉ Trung tâm ĐMST, BGH (Admin đã cho phép ở before)
        return $user->hasRole('center') || $user->hasRole('board');
    }

    /**
     * Quyết định xem user có thể phản biện ý tưởng không.
     */
    public function review(User $user, Idea $idea): bool
    {
        // Chỉ Trung tâm ĐMST, BGH hoặc role reviewer (nếu có) mới được review
        if (!$user->hasRole('center') && !$user->hasRole('board') && !$user->hasRole('reviewer')) {
            return false;
        }

        // Cho phép review khi ở các trạng thái cấp trên
        $reviewableStatuses = [
            'submitted_center',
            'needs_change_center',
            'submitted_board',
            'needs_change_board',
        ];

        return in_array($idea->status, $reviewableStatuses, true);
    }
}
