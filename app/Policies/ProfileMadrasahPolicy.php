<?php

namespace App\Policies;

use App\Models\ProfileMadrasah;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfileMadrasahPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_profile_madrasah');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProfileMadrasah $profileMadrasah): bool
    {
        return $user->can('view_profile_madrasah');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_profile_madrasah');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProfileMadrasah $profileMadrasah): bool
    {
        return $user->can('update_profile_madrasah');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProfileMadrasah $profileMadrasah): bool
    {
        return $user->can('delete_profile_madrasah');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProfileMadrasah $profileMadrasah): bool
    {
        return $user->can('restore_profile_madrasah');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProfileMadrasah $profileMadrasah): bool
    {
        return $user->can('force_delete_profile_madrasah');
    }
}