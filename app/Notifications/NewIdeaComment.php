<?php

namespace App\Notifications;

use App\Models\Idea;
use App\Models\IdeaComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewIdeaComment extends Notification
{
    use Queueable;

    public Idea $idea;
    public IdeaComment $comment;

    public function __construct(Idea $idea, IdeaComment $comment)
    {
        $this->idea = $idea;
        $this->comment = $comment;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $title = $this->idea->title;
        $author = $this->comment->user?->name ?? 'Thành viên';
        $snippet = mb_strimwidth((string) $this->comment->body, 0, 120, '…', 'UTF-8');

        return [
            'title' => 'Bình luận mới',
            'message' => "{$author} đã bình luận vào ý tưởng '{$title}': {$snippet}",
            'url' => route('my-ideas.show', $this->idea->id),
            'icon' => 'comment',
            'color' => 'blue',
        ];
    }
}

