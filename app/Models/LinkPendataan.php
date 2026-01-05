<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkPendataan extends Model
{
    protected $fillable = [
        'title',
        'url',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
