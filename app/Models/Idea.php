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
        'like_count',
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
     * Thành viên thường (không bao gồm mentor)
     */
    public function teamMembers()
    {
        return $this->members()->where('role_in_team', 'member');
    }

    /**
     * Danh sách cố vấn (mentor)
     */
    public function mentors()
    {
        return $this->members()->where('role_in_team', 'mentor');
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
        return $this->hasManyThrough(
            Review::class,
            ReviewAssignment::class,
            'idea_id',        // Foreign key on review_assignments table
            'assignment_id',  // Foreign key on reviews table
            'id',             // Local key on ideas table
            'id'              // Local key on review_assignments table
        );
    }

    /**
     * Ý tưởng thuộc về một khoa
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Ý tưởng thuộc về một danh mục
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Ý tưởng có nhiều tags (many-to-many)
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'idea_tag');
    }

    /**
     * Ý tưởng có nhiều attachments (polymorphic)
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Bình luận nội bộ (team_only) và công khai
     */
    public function comments()
    {
        return $this->hasMany(IdeaComment::class);
    }

    /**
     * Ý tưởng có nhiều lượt like (many-to-many với users)
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, 'idea_likes')->withTimestamps();
    }

    /**
     * Kiểm tra user đã like ý tưởng này chưa
     */
    public function isLikedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope: Chỉ lấy ý tưởng đã được duyệt cuối và công khai
     */
    public function scopePublicApproved($query)
    {
        return $query->where('status', 'approved_final')
            ->where('visibility', 'public');
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
        return $this->status === 'approved_final' || $this->status === 'approved_center';
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
