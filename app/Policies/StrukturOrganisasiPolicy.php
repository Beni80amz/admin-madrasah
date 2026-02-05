<?php

namespace App\Policies;

use App\Models\StrukturOrganisasi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StrukturOrganisasiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_struktur_organisasi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StrukturOrganisasi $strukturOrganisasi): bool
    {
        return $user->can('view_struktur_organisasi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_struktur_organisasi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StrukturOrganisasi $strukturOrganisasi): bool
    {
        return $user->can('update_struktur_organisasi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StrukturOrganisasi $strukturOrganisasi): bool
    {
        return $user->can('delete_struktur_organisasi');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StrukturOrganisasi $strukturOrganisasi): bool
    {
        return $user->can('restore_struktur_organisasi');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StrukturOrganisasi $strukturOrganisasi): bool
    {
        return $user->can('force_delete_struktur_organisasi');
    }
}