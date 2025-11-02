<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'review_id',
        'idea_id',
        'request_message',
        'is_resolved',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_resolved' => 'boolean',
        ];
    }

    /**
     * Yêu cầu chỉnh sửa thuộc về một review
     */
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    /**
     * Yêu cầu chỉnh sửa thuộc về một ý tưởng
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    /**
     * Helper: Đánh dấu đã được xử lý
     */
    public function markAsResolved(): bool
    {
        return $this->update(['is_resolved' => true]);
    }

    /**
     * Helper: Đánh dấu chưa được xử lý
     */
    public function markAsUnresolved(): bool
    {
        return $this->update(['is_resolved' => false]);
    }
}
