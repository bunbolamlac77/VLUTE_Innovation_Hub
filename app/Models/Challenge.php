<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

