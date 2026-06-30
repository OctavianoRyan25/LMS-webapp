<?php

namespace App\Notifications;

use App\Models\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Pengingat deadline tugas — dikirim H-1 atau H-3 sebelum batas waktu.
 */
class AssignmentDueSoon extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Assignment $assignment,
        private readonly int $daysLeft = 1,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $due = $this->assignment->due_date?->format('d M Y H:i');

        return [
            'type'       => 'assignment_due_soon',
            'title'      => 'Deadline Tugas Mendekat',
            'body'       => "Tugas \"{$this->assignment->title}\" akan berakhir dalam {$this->daysLeft} hari. Segera kumpulkan sebelum {$due}.",
            'days_left'  => $this->daysLeft,
            'assignment' => [
                'id'       => $this->assignment->id,
                'title'    => $this->assignment->title,
                'due_date' => $this->assignment->due_date?->toDateTimeString(),
            ],
            'icon'       => '⏰',
            'action_url' => null,
        ];
    }
}
