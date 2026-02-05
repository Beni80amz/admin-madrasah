<?php

namespace App\Policies;

use App\Models\FeeItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FeeItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_fee_item');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FeeItem $feeItem): bool
    {
        return $user->can('view_fee_item');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_fee_item');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FeeItem $feeItem): bool
    {
        return $user->can('update_fee_item');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FeeItem $feeItem): bool
    {
        return $user->can('delete_fee_item');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FeeItem $feeItem): bool
    {
        return $user->can('restore_fee_item');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FeeItem $feeItem): bool
    {
        return $user->can('force_delete_fee_item');
    }
}
