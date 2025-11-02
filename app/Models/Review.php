<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assignment_id',
        'overall_comment',
        'decision',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'decision' => 'string',
        ];
    }

    /**
     * Review thuộc về một review assignment
     */
    public function assignment()
    {
        return $this->belongsTo(ReviewAssignment::class);
    }

    /**
     * Review có nhiều change requests
     */
    public function changeRequests()
    {
        return $this->hasMany(ChangeRequest::class);
    }

    /**
     * Helper: Kiểm tra quyết định
     */
    public function isApproved(): bool
    {
        return $this->decision === 'approved';
    }

    public function needsChange(): bool
    {
        return $this->decision === 'needs_change';
    }

    public function isRejected(): bool
    {
        return $this->decision === 'rejected';
    }
}
