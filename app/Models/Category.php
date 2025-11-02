<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    /**
     * Danh mục có nhiều ý tưởng
     */
    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }
}

