<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Fee;

class FeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSchoolAdmin() || $user->isStudent();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Fee $fee): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            return $user->student && $user->student->id === $fee->student_id;
        }

        return $user->school_id === $fee->school_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSchoolAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Fee $fee): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isSchoolAdmin() && $user->school_id === $fee->school_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fee $fee): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isSchoolAdmin() && $user->school_id === $fee->school_id;
    }
}
