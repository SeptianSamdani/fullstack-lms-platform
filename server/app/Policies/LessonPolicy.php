<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LessonPolicy
{
    public function create(User $user, Module $module): bool
    {
        return $user->hasRole('admin') || $user->id === $module->course->instructor_id;
    }
    public function update(User $user, Lesson $lesson): bool
    {
        return $user->hasRole('admin') || $user->id === $lesson->module->course->instructor_id;
    }
    public function delete(User $user, Lesson $lesson): bool
    {
        return $this->update($user, $lesson);
    }
}
