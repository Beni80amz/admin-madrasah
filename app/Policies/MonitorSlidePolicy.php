<?php

namespace App\Policies;

use App\Models\MonitorSlide;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MonitorSlidePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only Superadmin (via Gate::before) or if we explicitly add permission later
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MonitorSlide $monitorSlide): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MonitorSlide $monitorSlide): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MonitorSlide $monitorSlide): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MonitorSlide $monitorSlide): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MonitorSlide $monitorSlide): bool
    {
        return false;
    }
}
