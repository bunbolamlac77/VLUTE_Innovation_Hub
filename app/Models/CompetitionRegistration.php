<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'user_id',
        'team_name',
        'notes',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Đăng ký thuộc về một cuộc thi
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Đăng ký thuộc về một user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Đăng ký có nhiều bài nộp
     */
    public function submissions()
    {
        return $this->hasMany(CompetitionSubmission::class, 'registration_id');
    }
}
