<?php

namespace App\Policies;

use App\Models\PpdbRegistration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PpdbRegistrationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_ppdb_registration');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PpdbRegistration $ppdbRegistration): bool
    {
        return $user->can('view_ppdb_registration');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_ppdb_registration');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PpdbRegistration $ppdbRegistration): bool
    {
        return $user->can('update_ppdb_registration');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PpdbRegistration $ppdbRegistration): bool
    {
        return $user->can('delete_ppdb_registration');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PpdbRegistration $ppdbRegistration): bool
    {
        return $user->can('restore_ppdb_registration');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PpdbRegistration $ppdbRegistration): bool
    {
        return $user->can('force_delete_ppdb_registration');
    }
}