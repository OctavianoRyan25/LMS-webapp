<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrasi ini:
 * 1. Drop tabel lessons (ada FK ke chapters)
 * 2. Drop tabel chapters
 * 3. Buat ulang tabel lessons dengan course_id langsung (tanpa chapter)
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus tabel lesson_files dulu (FK ke lessons)
        Schema::dropIfExists('lesson_files');

        // 2. Hapus tabel yang bergantung pada lessons
        Schema::dropIfExists('student_progress');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('quizzes');

        // 3. Hapus lessons & chapters
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('chapters');

        // 4. Buat ulang lessons — langsung ke course
        Schema::create('lessons', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_public_id')->nullable();        // Cloudinary public_id untuk delete
            $table->unsignedInteger('order')->default(0);
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->boolean('is_free_preview')->default(false);
            $table->timestamps();
        });

        // 5. Buat ulang lesson_files
        Schema::create('lesson_files', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('file_type', 20);                      // 'attachment', 'video'
            $table->string('file_name');
            $table->string('file_url');                           // full HTTPS URL Cloudinary
            $table->string('cloudinary_public_id');               // untuk delete
            $table->unsignedBigInteger('file_size')->nullable();  // bytes
            $table->timestamps();
        });

        // 6. Buat ulang student_progress
        Schema::create('student_progress', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'lesson_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_progress');
        Schema::dropIfExists('lesson_files');
        Schema::dropIfExists('lessons');
        // Tidak perlu recreate chapters — gunakan migrate:fresh untuk rollback penuh
    }
};
