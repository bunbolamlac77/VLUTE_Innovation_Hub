<?php

namespace App\Notifications;

use App\Models\IdeaInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvitationAccepted extends Notification
{
    use Queueable;

    public IdeaInvitation $invitation;

    public function __construct(IdeaInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $idea = $this->invitation->idea; // lazy ok
        $title = $idea?->title ?? 'Ý tưởng';
        $role = ($this->invitation->role ?? 'member') === 'mentor' ? 'Mentor' : 'Thành viên';
        $url = route('my-ideas.show', $this->invitation->idea_id);

        return [
            'title' => 'Lời mời đã được chấp nhận',
            'message' => "{$role} đã chấp nhận lời mời tham gia ý tưởng '{$title}'.",
            'url' => $url,
            'icon' => 'check',
            'color' => 'emerald',
        ];
    }
}

