<?php

namespace App\Policies;

use App\Models\Roadmap;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoadmapPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Roadmap $roadmap): bool
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
    public function update(User $user, Roadmap $roadmap): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Roadmap $roadmap): bool
    {
        return $user->user_handle === $roadmap->owner_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Roadmap $roadmap): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Roadmap $roadmap): bool
    {
        return false;
    }
}
