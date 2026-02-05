<?php

namespace App\Policies;

use App\Models\LinkPendataan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LinkPendataanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_link_pendataan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LinkPendataan $linkPendataan): bool
    {
        return $user->can('view_link_pendataan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_link_pendataan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LinkPendataan $linkPendataan): bool
    {
        return $user->can('update_link_pendataan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LinkPendataan $linkPendataan): bool
    {
        return $user->can('delete_link_pendataan');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LinkPendataan $linkPendataan): bool
    {
        return $user->can('restore_link_pendataan');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LinkPendataan $linkPendataan): bool
    {
        return $user->can('force_delete_link_pendataan');
    }
}
