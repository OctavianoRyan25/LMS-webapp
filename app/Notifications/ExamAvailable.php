<?php

namespace App\Notifications;

use App\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke siswa saat ujian baru dipublikasikan di kursus mereka.
 */
class ExamAvailable extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Exam $exam,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $course = $this->exam->course;

        return [
            'type'         => 'exam_available',
            'title'        => 'Ujian Baru Tersedia',
            'body'         => "Ujian \"{$this->exam->title}\" telah dibuka untuk kursus \"{$course->title}\"."
                . ($this->exam->end_date ? " Batas waktu: {$this->exam->end_date->format('d M Y H:i')}." : ''),
            'exam'         => [
                'id'         => $this->exam->id,
                'title'      => $this->exam->title,
                'start_date' => $this->exam->start_date?->toDateTimeString(),
                'end_date'   => $this->exam->end_date?->toDateTimeString(),
            ],
            'course'       => [
                'id'    => $course->id,
                'title' => $course->title,
            ],
            'icon'         => '🗒️',
            'action_url'   => null,
        ];
    }
}
