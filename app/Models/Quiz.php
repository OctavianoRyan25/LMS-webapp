<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id', 'title', 'description', 'questions', 'duration_minutes', 'passing_score'];

    protected $casts = [
        'questions' => 'array',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
