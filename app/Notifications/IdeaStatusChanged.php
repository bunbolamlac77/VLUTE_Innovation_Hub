<?php

namespace App\Notifications;

use App\Models\Idea;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class IdeaStatusChanged extends Notification
{
    use Queueable;

    public Idea $idea;
    public string $status;
    public ?string $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Idea $idea, string $status, ?string $customMessage = null)
    {
        $this->idea = $idea;
        $this->status = $status;
        $this->message = $customMessage;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // Lưu vào database để hiện quả chuông
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        // Tạo nội dung thông báo dựa trên status
        $msg = $this->message;

        if (!$msg) {
            $title = $this->idea?->title;
            $msg = match ($this->status) {
                'approved' => "Ý tưởng '{$title}' đã được duyệt!",
                'rejected' => "Ý tưởng '{$title}' bị từ chối.",
                'needs_change' => "Ý tưởng '{$title}' cần chỉnh sửa.",
                'submitted' => "Có ý tưởng mới '{$title}' vừa nộp.",
                default => "Cập nhật mới về ý tưởng '{$title}'",
            };
        }

        // Link để người dùng bấm vào xem
        $url = route('my-ideas.show', $this->idea?->id);

        // Nếu là admin/reviewer thì trỏ về trang quản lý khi có bài mới nộp
        if (method_exists($notifiable, 'hasRole') && ($notifiable->hasRole('admin') || $notifiable->hasRole('staff') || $notifiable->hasRole('center'))) {
            if ($this->status === 'submitted') {
                $url = route('manage.review.form', $this->idea?->id);
            }
        }

        return [
            'title' => 'Thông báo',
            'message' => $msg,
            'url' => $url,
            'icon' => 'info',
            'color' => 'blue',
        ];
    }
}

