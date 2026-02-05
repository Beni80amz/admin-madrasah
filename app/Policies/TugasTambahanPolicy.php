<?php

namespace App\Policies;

use App\Models\TugasTambahan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TugasTambahanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_tugas_tambahan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TugasTambahan $tugasTambahan): bool
    {
        return $user->can('view_tugas_tambahan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_tugas_tambahan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TugasTambahan $tugasTambahan): bool
    {
        return $user->can('update_tugas_tambahan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TugasTambahan $tugasTambahan): bool
    {
        return $user->can('delete_tugas_tambahan');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TugasTambahan $tugasTambahan): bool
    {
        return $user->can('restore_tugas_tambahan');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TugasTambahan $tugasTambahan): bool
    {
        return $user->can('force_delete_tugas_tambahan');
    }
}