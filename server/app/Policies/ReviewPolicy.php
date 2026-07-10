<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * User hanya boleh membuat review jika sudah enroll di course tersebut
     * dan belum pernah memberi review sebelumnya.
     */
    public function create(User $user, Course $course): bool
    {
        $isEnrolled = $user->enrollments()->where('course_id', $course->id)->exists();
        if (!$isEnrolled) {
            return false;
        }

        return !Review::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();
    }

    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->hasRole('admin') || $user->id === $review->user_id;
    }
}