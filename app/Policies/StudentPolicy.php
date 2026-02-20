<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSchoolAdmin() || $user->isTeacher();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            return $user->student && $user->student->id === $student->id;
        }

        return $user->school_id === $student->school_id;
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
    public function update(User $user, Student $student): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isSchoolAdmin() && $user->school_id === $student->school_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isSchoolAdmin() && $user->school_id === $student->school_id;
    }
}
