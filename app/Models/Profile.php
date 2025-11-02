<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_code',
        'faculty_id',
        'organization_id',
        'company_name',
        'position',
        'interest_field',
        'phone',
        'bio',
    ];

    /**
     * Profile thuộc về một User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Profile có thể thuộc về một Faculty
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Profile có thể thuộc về một Organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

