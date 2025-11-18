<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScientificNew extends Model
{
    protected $table = 'scientific_news';
    
    protected $fillable = [
        'title',
        'description',
        'content',
        'author',
        'source',
        'image_url',
        'published_date',
        'category',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];
}
