<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'uploaded_by',
        'filename',
        'path',
        'mime_type',
        'size',
    ];

    /**
     * Attachment thuộc về một model (polymorphic)
     */
    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     * Attachment được upload bởi một User
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

