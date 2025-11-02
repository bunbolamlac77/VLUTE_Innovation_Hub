<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'website',
        'contact_email',
        'description',
    ];

    /**
     * Organization có nhiều profiles (users thuộc về organization)
     */
    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    /**
     * Organization có nhiều ý tưởng (nếu type = faculty)
     */
    public function ideas()
    {
        return $this->hasMany(Idea::class, 'faculty_id');
    }
}

