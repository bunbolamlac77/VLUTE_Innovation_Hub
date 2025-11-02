<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idea_id',
        'reviewer_id',
        'assigned_by',
        'review_level',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'review_level' => 'string',
            'status' => 'string',
        ];
    }

    /**
     * Phân công duyệt thuộc về một ý tưởng
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    /**
     * Người được phân công duyệt
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Người phân công
     */
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Phân công có một review
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Helper: Kiểm tra trạng thái
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Helper: Đánh dấu hoàn thành
     */
    public function markAsCompleted(): bool
    {
        return $this->update(['status' => 'completed']);
    }
}
