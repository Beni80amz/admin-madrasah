<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TugasPokok extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'keterangan',
    ];
}
