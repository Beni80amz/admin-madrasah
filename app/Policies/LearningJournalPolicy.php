<?php

namespace App\Policies;

use App\Models\LearningJournal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LearningJournalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Superadmin', 'super_admin', 'Guru']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LearningJournal $learningJournal): bool
    {
        return $user->hasRole(['Superadmin', 'super_admin', 'Guru']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['Superadmin', 'super_admin', 'Guru']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LearningJournal $learningJournal): bool
    {
        return $user->hasRole(['Superadmin', 'super_admin', 'Guru']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LearningJournal $learningJournal): bool
    {
        return $user->hasRole(['Superadmin', 'super_admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LearningJournal $learningJournal): bool
    {
        return $user->hasRole(['Superadmin', 'super_admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LearningJournal $learningJournal): bool
    {
        return $user->hasRole(['Superadmin', 'super_admin']);
    }
}
