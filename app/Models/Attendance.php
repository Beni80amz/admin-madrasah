<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'status',
        'keterlambatan',
        'lembur',
        'lat_in',
        'long_in',
        'photo_in',
        'lat_out',
        'long_out',
        'photo_out',
        'device_id',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
