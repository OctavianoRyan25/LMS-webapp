<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ExamSubmission extends Model
{
    protected $fillable = [
        'exam_id',
        'user_id',
        'answers',
        'score',
        'is_passed',
        'started_at',
        'submitted_at',
    ];

    protected $casts = [
        'answers'      => 'array',
        'is_passed'    => 'boolean',
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
