<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'description', 'questions', 'duration_minutes', 'passing_score', 'start_date', 'end_date'];

    protected $casts = [
        'questions' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
