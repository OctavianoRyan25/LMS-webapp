<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LessonFile extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id', 'file_type', 'file_path', 'file_url', 'file_name', 'file_size', 'cloudinary_public_id'];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
