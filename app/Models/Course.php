<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'thumbnail',
        'thumbnail_url',
        'status',
        'category_id',
        'level',
        'price',
        'duration_hours',
        'instructor_id',
        'has_certificate',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'has_certificate' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}
