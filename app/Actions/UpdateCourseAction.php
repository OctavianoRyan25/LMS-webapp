<?php

namespace App\Actions;

use App\Models\Course;
use Illuminate\Support\Facades\DB;

final class UpdateCourseAction
{
    public function handle(Course $course, array $data): Course
    {
        return DB::transaction(function () use ($course, $data): Course {
            // Gunakan array_filter dengan callback supaya nilai 0 dan false
            // tidak ikut terbuang (hanya null yang dibuang).
            $filtered = array_filter($data, fn ($v) => $v !== null);

            $course->update($filtered);

            return $course->fresh();
        });
    }
}
