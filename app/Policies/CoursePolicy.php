<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

final class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, ['admin', 'tutor']);
    }

    public function view(User $user, Course $course): bool
    {
        return $user->role->name === 'admin' || $course->creator_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role->name, ['admin', 'tutor']);
    }

    public function update(User $user, Course $course): bool
    {
        return $user->role->name === 'admin' || $course->creator_id === $user->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->role->name === 'admin' || $course->creator_id === $user->id;
    }

    public function restore(User $user, Course $course): bool
    {
        return $user->role->name === 'admin';
    }

    public function forceDelete(User $user, Course $course): bool
    {
        return $user->role->name === 'admin';
    }
}
