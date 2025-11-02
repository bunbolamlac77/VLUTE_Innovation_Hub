<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idea_id',
        'user_id',
        'role_in_team',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role_in_team' => 'string',
        ];
    }

    /**
     * Thành viên thuộc về một ý tưởng
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    /**
     * Thành viên là một User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper: Kiểm tra có phải owner không
     */
    public function isOwner(): bool
    {
        return $this->role_in_team === 'owner';
    }
}
