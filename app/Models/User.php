<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',

        // Các trường phục vụ phân quyền & duyệt
        'role',              // student|staff|enterprise|admin
        'approval_status',   // approved|pending|rejected
        'is_active',         // true|false - khóa/mở khóa tài khoản
        'company',           // DN (tuỳ chọn)
        'position',          // DN (tuỳ chọn)
        'interest',          // DN (tuỳ chọn)
        // Lưu ý: approved_by thường set từ server (admin), có thể không để fillable
        // 'approved_by',
        // 'approved_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Helper: Đã được duyệt chưa?
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Helper: Role này có cần duyệt thủ công không?
     * - student: auto (không cần duyệt)
     * - staff, enterprise: cần duyệt
     * - admin: do sysadmin gán
     */
    public function needsApproval(): bool
    {
        return in_array($this->role, ['staff', 'enterprise'], true);
    }
    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class)->withTimestamps()->withPivot('assigned_by');
    }

    public function hasRole(string $slug): bool
    {
        // Kiểm tra field role trước (nhanh hơn)
        if ($this->role === $slug) {
            return true;
        }

        // Kiểm tra relationship roles (nếu relationship chưa được load, Laravel sẽ tự động load)
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function syncRoles(array $slugs, ?int $by = null): void
    {
        $roleIds = \App\Models\Role::whereIn('slug', $slugs)->pluck('id')->all();
        // Đồng bộ pivot
        $this->roles()->sync(array_fill_keys($roleIds, ['assigned_by' => $by]));
    }
    // Việt hoá nhãn vai trò
    public static function roleLabel(string $slug): string
    {
        return match ($slug) {
            'student' => 'Sinh viên',
            'staff' => 'Giảng viên',
            'center' => 'Trung tâm ĐMST',
            'board' => 'Ban giám hiệu',
            'enterprise' => 'Doanh nghiệp',
            'reviewer' => 'Người phản biện',
            'admin' => 'Quản trị',
            default => ucfirst($slug),
        };
    }

    // Dùng trong Blade: {{ $user->role_label }}
    public function getRoleLabelAttribute(): string
    {
        return self::roleLabel($this->role ?? '');
    }

    // Helper: Kiểm tra tài khoản có bị khóa không?
    public function isActive(): bool
    {
        return $this->is_active ?? true;
    }

    // Helper: Khóa tài khoản
    public function lock(): bool
    {
        return $this->update(['is_active' => false]);
    }

    // Helper: Mở khóa tài khoản
    public function unlock(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * User có nhiều ý tưởng (là chủ sở hữu)
     */
    public function ideas()
    {
        return $this->hasMany(Idea::class, 'owner_id');
    }

    /**
     * User là thành viên của nhiều ý tưởng (qua idea_members)
     */
    public function ideaMemberships()
    {
        return $this->hasMany(IdeaMember::class);
    }

    /**
     * User gửi nhiều lời mời
     */
    public function sentInvitations()
    {
        return $this->hasMany(IdeaInvitation::class, 'invited_by');
    }

    /**
     * User được phân công duyệt nhiều ý tưởng
     */
    public function reviewAssignments()
    {
        return $this->hasMany(ReviewAssignment::class, 'reviewer_id');
    }

    /**
     * User có một Profile
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * User đã upload nhiều attachments
     */
    public function uploadedAttachments()
    {
        return $this->hasMany(Attachment::class, 'uploaded_by');
    }
}