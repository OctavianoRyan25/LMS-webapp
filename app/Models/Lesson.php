<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['chapter_id', 'prev_lesson_id', 'next_lesson_id', 'title', 'content', 'order'];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function prevLesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'prev_lesson_id');
    }

    public function nextLesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'next_lesson_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(LessonFile::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(StudentProgress::class);
    }
}
