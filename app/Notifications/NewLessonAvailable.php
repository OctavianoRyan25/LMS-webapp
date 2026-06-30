<?php

namespace App\Notifications;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke semua siswa yang terdaftar di kursus saat lesson baru ditambahkan.
 */
class NewLessonAvailable extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Lesson $lesson,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $course = $this->lesson->course;

        return [
            'type'       => 'new_lesson',
            'title'      => 'Materi Baru Tersedia',
            'body'       => "Materi baru \"{$this->lesson->title}\" telah ditambahkan pada kursus \"{$course->title}\".",
            'lesson'     => [
                'id'    => $this->lesson->id,
                'title' => $this->lesson->title,
            ],
            'course'     => [
                'id'    => $course->id,
                'title' => $course->title,
            ],
            'icon'       => '📚',
            'action_url' => null,
        ];
    }
}
