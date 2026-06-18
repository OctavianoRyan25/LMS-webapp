<?php

namespace App\Actions;

use App\Models\Course;
use Illuminate\Support\Facades\DB;

final class CreateCourseAction
{
    public function handle(array $data, int $creatorId): Course
    {
        return DB::transaction(function () use ($data, $creatorId): Course {
            return Course::query()->create([
                'creator_id'      => $creatorId,
                'title'           => $data['title'],
                'description'     => $data['description'] ?? null,
                'thumbnail'       => $data['thumbnail'] ?? null,
                'thumbnail_url'   => $data['thumbnail_url'] ?? null,
                'status'          => $data['status'] ?? 'draft',
                'category_id'     => $data['category_id'] ?? null,
                'level'           => $data['level'] ?? null,
                'price'           => $data['price'] ?? 0,
                'duration_hours'  => $data['duration_hours'] ?? null,
                'instructor_id'   => $data['instructor_id'] ?? null,
                'has_certificate' => $data['has_certificate'] ?? true,
            ]);
        });
    }
}
