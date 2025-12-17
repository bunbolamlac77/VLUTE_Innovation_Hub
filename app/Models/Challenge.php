<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'problem_statement',
        'requirements',
        'image',
        'image_url',
        'deadline',
        'reward',
        'status',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'status' => 'string',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function submissions()
    {
        return $this->hasMany(ChallengeSubmission::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Thumbnail thông minh cho Challenge:
     * - Nếu không có image: dùng ảnh mặc định
     * - Nếu image là URL: trả về trực tiếp
     * - Nếu là path nội bộ: asset('storage/...')
     */
    public function getThumbnailUrlAttribute(): string
    {
        // Ưu tiên image_url, nếu không có thì dùng image
        $image = $this->image_url ?? $this->image;
        
        if (!$image) {
            return asset('images/default-challenge.png');
        }

        $image = trim((string) $image);

        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }

        return asset('storage/' . ltrim($image, '/'));
    }
}

