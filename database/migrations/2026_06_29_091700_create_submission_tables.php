<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Membuat tabel submission untuk Quiz, Assignment, dan Exam.
 *
 * Format soal (questions JSON di tabel master):
 * [
 *   { "question": "...", "options": ["A","B","C","D"], "correct": 0 }
 * ]
 *
 * Format jawaban siswa (answers JSON di submission):
 * [0, 2, 1, 3, ...]   → array index jawaban yang dipilih per soal
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Quiz Submissions ─────────────────────────────────────────────────
        Schema::create('quiz_submissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('answers');                         // [0,2,1,...] index jawaban
            $table->unsignedTinyInteger('score')->default(0); // 0-100
            $table->boolean('is_passed')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->unique(['quiz_id', 'user_id']);          // 1 submission per siswa per quiz
        });

        // ── Assignment Submissions ───────────────────────────────────────────
        Schema::create('assignment_submissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');                         // jawaban esai siswa
            $table->unsignedTinyInteger('score')->nullable(); // 0-100, diisi tutor
            $table->text('feedback')->nullable();             // komentar tutor
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            $table->unique(['assignment_id', 'user_id']);
        });

        // ── Exam Submissions ─────────────────────────────────────────────────
        Schema::create('exam_submissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('answers');
            $table->unsignedTinyInteger('score')->default(0);
            $table->boolean('is_passed')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->unique(['exam_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_submissions');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('quiz_submissions');
    }
};
