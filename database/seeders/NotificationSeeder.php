<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeder untuk mengisi contoh notifikasi pada user admin/pertama.
 * Jalankan: php artisan db:seed --class=NotificationSeeder
 */
class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->warn('Tidak ada user. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $samples = [
            [
                'type'  => 'assignment_graded',
                'title' => 'Tugas Sudah Dinilai',
                'body'  => 'Tugas "Praktik HTML Dasar" kamu sudah dinilai. Nilai: 88',
                'score' => 88,
                'is_passed' => true,
                'feedback'  => 'Kerja bagus! Struktur HTML sudah rapi.',
                'assignment' => ['id' => 1, 'title' => 'Praktik HTML Dasar'],
                'icon'  => '📝',
                'action_url' => null,
            ],
            [
                'type'  => 'quiz_completed',
                'title' => 'Hasil Kuis Tersedia',
                'body'  => 'Kuis "Bab 2 — CSS Selector" selesai dinilai. Skor: 75 — LULUS ✅',
                'score' => 75,
                'is_passed' => true,
                'quiz'  => ['id' => 1, 'title' => 'Bab 2 — CSS Selector'],
                'icon'  => '🎯',
                'action_url' => null,
            ],
            [
                'type'  => 'exam_available',
                'title' => 'Ujian Baru Tersedia',
                'body'  => 'Ujian "UTS Web Development" telah dibuka. Batas waktu: 15 Jul 2026 23:59.',
                'exam'  => ['id' => 1, 'title' => 'UTS Web Development', 'start_date' => now()->toDateTimeString(), 'end_date' => now()->addDays(7)->toDateTimeString()],
                'course' => ['id' => 1, 'title' => 'Web Development Bootcamp'],
                'icon'  => '🗒️',
                'action_url' => null,
            ],
            [
                'type'  => 'new_lesson',
                'title' => 'Materi Baru Tersedia',
                'body'  => 'Materi baru "Flexbox dan Grid CSS" telah ditambahkan pada kursus "Web Development Bootcamp".',
                'lesson' => ['id' => 1, 'title' => 'Flexbox dan Grid CSS'],
                'course' => ['id' => 1, 'title' => 'Web Development Bootcamp'],
                'icon'  => '📚',
                'action_url' => null,
            ],
            [
                'type'      => 'assignment_due_soon',
                'title'     => 'Deadline Tugas Mendekat',
                'body'      => 'Tugas "Membuat Landing Page" akan berakhir dalam 1 hari. Segera kumpulkan!',
                'days_left' => 1,
                'assignment' => ['id' => 2, 'title' => 'Membuat Landing Page', 'due_date' => now()->addDay()->toDateTimeString()],
                'icon'      => '⏰',
                'action_url' => null,
            ],
        ];

        foreach ($samples as $i => $data) {
            $user->notifications()->create([
                'id'              => Str::uuid(),
                'type'            => 'App\\Notifications\\DatabaseNotification',
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => $data,
                'read_at'         => $i >= 3 ? now()->subHour() : null, // 2 terakhir sudah dibaca
                'created_at'      => now()->subMinutes(($i + 1) * 20),
                'updated_at'      => now()->subMinutes(($i + 1) * 20),
            ]);
        }

        $this->command->info("✅ {$user->name}: 5 notifikasi contoh ditambahkan (3 unread, 2 read).");
    }
}
