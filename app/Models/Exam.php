<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'questions',    // JSON: [{question,options:[],correct:int}]
        'duration_minutes',
        'passing_score',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'questions'  => 'array',
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ExamSubmission::class);
    }

    public function calculateScore(array $answers): int
    {
        $questions = $this->questions ?? [];
        $total     = count($questions);

        if ($total === 0) return 0;

        $correct = 0;
        foreach ($questions as $i => $q) {
            if (isset($answers[$i]) && (int) $answers[$i] === (int) $q['correct']) {
                $correct++;
            }
        }

        return (int) round(($correct / $total) * 100);
    }

    public function avgScore(): float
    {
        return round($this->submissions()->avg('score') ?? 0, 1);
    }

    public function passRate(): float
    {
        $total = $this->submissions()->count();
        if ($total === 0) return 0;
        return round(($this->submissions()->where('is_passed', true)->count() / $total) * 100, 1);
    }

    public function isActive(): bool
    {
        $now = now();
        return (! $this->start_date || $this->start_date <= $now)
            && (! $this->end_date || $this->end_date >= $now);
    }
}
