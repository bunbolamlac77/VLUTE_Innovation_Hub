<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'title',
        'abstract',
    ];

    /**
     * Bài nộp thuộc về một đăng ký
     */
    public function registration()
    {
        return $this->belongsTo(CompetitionRegistration::class);
    }

    /**
     * Bài nộp có nhiều attachments (polymorphic)
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
