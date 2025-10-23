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
        if ($this->role === $slug)
            return true; // vai chính, để tương thích middleware cũ
        return $this->roles->contains(fn($r) => $r->slug === $slug);
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
}