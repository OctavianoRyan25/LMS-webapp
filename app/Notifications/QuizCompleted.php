<?php

namespace App\Notifications;

use App\Models\QuizSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke siswa setelah sistem otomatis menilai kuis mereka.
 */
class QuizCompleted extends Notification
{
    use Queueable;

    public function __construct(
        private readonly QuizSubmission $submission,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $quiz   = $this->submission->quiz;
        $status = $this->submission->is_passed ? 'LULUS ✅' : 'Belum Lulus ❌';

        return [
            'type'       => 'quiz_completed',
            'title'      => 'Hasil Kuis Tersedia',
            'body'       => "Kuis \"{$quiz->title}\" selesai dinilai. Skor: {$this->submission->score} — {$status}",
            'score'      => $this->submission->score,
            'is_passed'  => $this->submission->is_passed,
            'quiz'       => [
                'id'    => $quiz->id,
                'title' => $quiz->title,
            ],
            'icon'       => '🎯',
            'action_url' => null,
        ];
    }
}
