<?php

namespace App\Notifications;

use App\Models\AssignmentSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke siswa saat tutor/admin memberikan nilai pada pengumpulan tugas.
 */
class AssignmentGraded extends Notification
{
    use Queueable;

    public function __construct(
        private readonly AssignmentSubmission $submission,
    ) {}

    /** Channel yang digunakan (database = notif web, 'fcm' bisa ditambah untuk HP) */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $assignment = $this->submission->assignment;

        return [
            'type'        => 'assignment_graded',
            'title'       => 'Tugas Sudah Dinilai',
            'body'        => "Tugas \"{$assignment->title}\" kamu sudah dinilai. Nilai: {$this->submission->score}",
            'score'       => $this->submission->score,
            'feedback'    => $this->submission->feedback,
            'assignment'  => [
                'id'    => $assignment->id,
                'title' => $assignment->title,
            ],
            'icon'        => '📝',
            'action_url'  => null, // isi dengan route sisi siswa jika ada
        ];
    }
}
