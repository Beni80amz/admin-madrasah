<?php

namespace App\Policies;

use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MataPelajaranPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_mata_pelajaran');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MataPelajaran $mataPelajaran): bool
    {
        return $user->can('view_mata_pelajaran');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_mata_pelajaran');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MataPelajaran $mataPelajaran): bool
    {
        return $user->can('update_mata_pelajaran');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MataPelajaran $mataPelajaran): bool
    {
        return $user->can('delete_mata_pelajaran');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MataPelajaran $mataPelajaran): bool
    {
        return $user->can('restore_mata_pelajaran');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MataPelajaran $mataPelajaran): bool
    {
        return $user->can('force_delete_mata_pelajaran');
    }
}