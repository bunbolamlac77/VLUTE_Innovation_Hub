<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IdeaInvitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idea_id',
        'invited_by',
        'email',
        'token',
        'status',
        'expires_at',
        'role',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'status' => 'string',
        ];
    }

    /**
     * Boot method để tự động tạo token khi tạo invitation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            if (empty($invitation->token)) {
                $invitation->token = Str::random(64);
            }
        });
    }

    /**
     * Lời mời thuộc về một ý tưởng
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    /**
     * Người gửi lời mời
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Helper: Kiểm tra lời mời còn hiệu lực không
     */
    public function isValid(): bool
    {
        return $this->status === 'pending' &&
            ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Helper: Đánh dấu đã chấp nhận
     */
    public function markAsAccepted(): bool
    {
        return $this->update(['status' => 'accepted']);
    }

    /**
     * Helper: Đánh dấu đã từ chối
     */
    public function markAsDeclined(): bool
    {
        return $this->update(['status' => 'declined']);
    }
}
