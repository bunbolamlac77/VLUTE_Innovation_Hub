<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    /**
     * Tag có nhiều ý tưởng (many-to-many)
     */
    public function ideas()
    {
        return $this->belongsToMany(Idea::class, 'idea_tag');
    }
}

