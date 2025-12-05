<?php

namespace App\Http\Controllers;

use App\Models\IdeaInvitation;
use App\Models\IdeaMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InvitationAccepted;

class IdeaInvitationController extends Controller
{
    /**
     * Chấp nhận lời mời tham gia ý tưởng
     */
    public function accept($token)
    {
        $invitation = IdeaInvitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        // Kiểm tra lời mời còn hiệu lực
        if (!$invitation->isValid()) {
            return redirect()->route('welcome')
                ->with('error', 'Lời mời đã hết hạn hoặc không còn hợp lệ.');
        }

        // Tìm user theo email (có thể user chưa đăng ký)
        $user = User::where('email', $invitation->email)->first();

        // Nếu user chưa tồn tại, yêu cầu đăng ký
        if (!$user) {
            return redirect()->route('register')
                ->with('message', 'Vui lòng đăng ký tài khoản để chấp nhận lời mời. Email: ' . $invitation->email);
        }

        // Kiểm tra user đã đăng nhập chưa
        if (!Auth::check() || Auth::user()->id !== $user->id) {
            return redirect()->route('login')
                ->with('message', 'Vui lòng đăng nhập với tài khoản ' . $invitation->email . ' để chấp nhận lời mời.');
        }

        // Kiểm tra user đã là member chưa
        $existingMember = IdeaMember::where('idea_id', $invitation->idea_id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingMember) {
            // Đánh dấu invitation là accepted
            $invitation->markAsAccepted();
            return redirect()->route('my-ideas.show', $invitation->idea_id)
                ->with('status', 'Bạn đã là thành viên của ý tưởng này.');
        }

        // Nếu lời mời là Mentor, đảm bảo user là giảng viên (staff)
        if (($invitation->role ?? 'member') === 'mentor') {
            if (!$user->hasRole('staff')) {
                return redirect()->route('welcome')
                    ->with('error', 'Tài khoản của bạn không có quyền trở thành Cố vấn (Mentor). Vui lòng liên hệ quản trị.');
            }
        }

        // Tạo IdeaMember với đúng vai trò
        IdeaMember::create([
            'idea_id' => $invitation->idea_id,
            'user_id' => $user->id,
            'role_in_team' => $invitation->role ?? 'member',
        ]);

        // Đánh dấu invitation là accepted
        $invitation->markAsAccepted();

        // Thông báo cho chủ ý tưởng (và nhóm) nếu mentor/member chấp nhận
        try {
            $invitation->loadMissing('idea.owner');
            $owner = $invitation->idea?->owner;
            if ($owner) {
                $owner->notify(new InvitationAccepted($invitation));
            }
        } catch (\Throwable $e) {
            // ignore notify error
        }

        return redirect()->route('my-ideas.show', $invitation->idea_id)
            ->with('status', 'Bạn đã chấp nhận lời mời tham gia ý tưởng thành công!');
    }

    /**
     * Từ chối lời mời tham gia ý tưởng
     */
    public function decline($token)
    {
        $invitation = IdeaInvitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        // Đánh dấu invitation là declined
        $invitation->markAsDeclined();

        return redirect()->route('welcome')
            ->with('status', 'Bạn đã từ chối lời mời tham gia ý tưởng.');
    }
}

