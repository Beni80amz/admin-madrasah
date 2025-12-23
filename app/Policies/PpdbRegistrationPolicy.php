<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PpdbRegistration;
use Illuminate\Auth\Access\Response;

class PpdbRegistrationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin PPDB');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PpdbRegistration $model): bool
    {
        return $user->hasRole('Admin PPDB');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Admin PPDB');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PpdbRegistration $model): bool
    {
        return $user->hasRole('Admin PPDB');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PpdbRegistration $model): bool
    {
        return $user->hasRole('Admin PPDB');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PpdbRegistration $model): bool
    {
        return $user->hasRole('Admin PPDB');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PpdbRegistration $model): bool
    {
        return $user->hasRole('Admin PPDB');
    }
}