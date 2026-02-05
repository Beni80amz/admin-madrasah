<?php

namespace App\Policies;

use App\Models\StudentBill;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentBillPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_student_bill');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentBill $studentBill): bool
    {
        return $user->can('view_student_bill');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_student_bill');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentBill $studentBill): bool
    {
        return $user->can('update_student_bill');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentBill $studentBill): bool
    {
        return $user->can('delete_student_bill');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentBill $studentBill): bool
    {
        return $user->can('restore_student_bill');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentBill $studentBill): bool
    {
        return $user->can('force_delete_student_bill');
    }
}
