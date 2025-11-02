<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Idea extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'title',
        'slug',
        'description',
        'summary',
        'content',
        'status',
        'visibility',
        'faculty_id',
        'category_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'string',
            'visibility' => 'string',
        ];
    }

    /**
     * Ý tưởng thuộc về người tạo (User)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Ý tưởng có nhiều thành viên nhóm
     */
    public function members()
    {
        return $this->hasMany(IdeaMember::class);
    }

    /**
     * Ý tưởng có nhiều lời mời
     */
    public function invitations()
    {
        return $this->hasMany(IdeaInvitation::class);
    }

    /**
     * Ý tưởng có nhiều yêu cầu chỉnh sửa
     */
    public function changeRequests()
    {
        return $this->hasMany(ChangeRequest::class);
    }

    /**
     * Ý tưởng có nhiều phân công duyệt
     */
    public function reviewAssignments()
    {
        return $this->hasMany(ReviewAssignment::class);
    }

    /**
     * Lấy các reviews thông qua review assignments
     */
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, ReviewAssignment::class);
    }

    /**
     * Helper: Kiểm tra trạng thái ý tưởng
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isSubmitted(): bool
    {
        return str_starts_with($this->status, 'submitted_');
    }

    public function isApproved(): bool
    {
        return str_ends_with($this->status, '_final') || str_ends_with($this->status, '_gv') || str_ends_with($this->status, '_center');
    }

    public function needsChange(): bool
    {
        return str_contains($this->status, 'needs_change');
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
