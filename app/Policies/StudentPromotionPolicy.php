<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPromotionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_student_promotion');
    }
}
