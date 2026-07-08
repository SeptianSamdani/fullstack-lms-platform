<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('instructor');
    }

    public function update(User $user, Course $course): bool
    {
        return $user->hasRole('admin') || ($user->can('update courses') && $user->id === $course->instructor_id);
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->hasRole('admin') || ($user->can('delete courses') && $user->id === $course->instructor_id);
    }
}