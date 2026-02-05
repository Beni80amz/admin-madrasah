<?php

namespace App\Policies;

use App\Models\Ekstrakurikuler;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EkstrakurikulerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_ekstrakurikuler');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ekstrakurikuler $ekstrakurikuler): bool
    {
        return $user->can('view_ekstrakurikuler');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_ekstrakurikuler');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ekstrakurikuler $ekstrakurikuler): bool
    {
        return $user->can('update_ekstrakurikuler');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ekstrakurikuler $ekstrakurikuler): bool
    {
        return $user->can('delete_ekstrakurikuler');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ekstrakurikuler $ekstrakurikuler): bool
    {
        return $user->can('restore_ekstrakurikuler');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ekstrakurikuler $ekstrakurikuler): bool
    {
        return $user->can('force_delete_ekstrakurikuler');
    }
}