<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Competition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'banner_url',
        'start_date',
        'end_date',
        'status',
        'approval_level',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Lấy user đã tạo cuộc thi này.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Lấy tất cả các đơn đăng ký của cuộc thi này.
     */
    public function registrations()
    {
        return $this->hasMany(CompetitionRegistration::class);
    }

    /**
     * Thumbnail thông minh cho cuộc thi:
     * - Nếu không có banner_url: dùng ảnh mặc định
     * - Nếu banner_url là URL: trả về trực tiếp
     * - Nếu là path nội bộ: asset('storage/...')
     */
    public function getThumbnailUrlAttribute(): string
    {
        if (!$this->banner_url) {
            return asset('images/default-competition.png');
        }

        $banner = trim((string) $this->banner_url);

        if (Str::startsWith($banner, ['http://', 'https://'])) {
            return $banner;
        }

        return asset('storage/' . ltrim($banner, '/'));
    }
}
