<?php

namespace App\Policies;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AchievementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_achievement');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Achievement $achievement): bool
    {
        return $user->can('view_achievement');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_achievement');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Achievement $achievement): bool
    {
        return $user->can('update_achievement');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Achievement $achievement): bool
    {
        return $user->can('delete_achievement');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Achievement $achievement): bool
    {
        return $user->can('restore_achievement');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Achievement $achievement): bool
    {
        return $user->can('force_delete_achievement');
    }
}