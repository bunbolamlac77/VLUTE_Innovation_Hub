<?php

namespace App\Notifications;

use App\Models\IdeaInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MentorInvited extends Notification
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
        $idea = $this->invitation->idea; // may be loaded lazily
        $title = $idea?->title ?? 'Ý tưởng';
        $url = route('invitations.accept', $this->invitation->token);

        return [
            'title' => 'Lời mời Mentor',
            'message' => "Bạn được mời làm Cố vấn (Mentor) cho ý tưởng '{$title}'.",
            'url' => $url,
            'icon' => 'mentor',
            'color' => 'indigo',
        ];
    }
}

