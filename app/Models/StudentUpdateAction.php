<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentUpdateAction extends Model
{
    protected $fillable = [
        'student_id',
        'user_id',
        'changes',
        'status',
        'verifier_id',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'changes' => 'array',
        'verified_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }
}
