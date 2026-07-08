<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ModulePolicy
{
    public function create(User $user, Course $course): bool
    {
        return $user->hasRole('admin') || $user->id === $course->instructor_id;
    }
    public function update(User $user, Module $module): bool
    {
        return $user->hasRole('admin') || $user->id === $module->course->instructor_id;
    }
    public function delete(User $user, Module $module): bool
    {
        return $this->update($user, $module);
    }
}
