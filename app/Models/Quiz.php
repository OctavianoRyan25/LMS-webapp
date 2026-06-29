<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'questions',    // JSON: [{question,options:[],correct:int}]
        'duration_minutes',
        'passing_score',
    ];

    protected $casts = [
        'questions' => 'array',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(QuizSubmission::class);
    }

    /** Hitung skor otomatis dari jawaban siswa */
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

    // ── Analytics helpers ─────────────────────────────────────────────────────

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
}
