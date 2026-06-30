<?php

namespace App\Notifications;

use App\Models\ExamSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke siswa setelah sistem menilai ujian mereka.
 */
class ExamCompleted extends Notification
{
    use Queueable;

    public function __construct(
        private readonly ExamSubmission $submission,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $exam   = $this->submission->exam;
        $status = $this->submission->is_passed ? 'LULUS ✅' : 'Belum Lulus ❌';

        return [
            'type'       => 'exam_completed',
            'title'      => 'Hasil Ujian Tersedia',
            'body'       => "Ujian \"{$exam->title}\" selesai dinilai. Skor: {$this->submission->score} — {$status}",
            'score'      => $this->submission->score,
            'is_passed'  => $this->submission->is_passed,
            'exam'       => [
                'id'    => $exam->id,
                'title' => $exam->title,
            ],
            'icon'       => '📋',
            'action_url' => null,
        ];
    }
}
