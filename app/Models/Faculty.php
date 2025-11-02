<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'sort_order',
    ];

    /**
     * Khoa có nhiều ý tưởng
     */
    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }
}

