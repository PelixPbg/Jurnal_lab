<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JadwalBaruNotification extends Notification
{
    use Queueable;

    public $scheduleId;

    // Terima ID jadwal saat notifikasi dipanggil
    public function __construct($scheduleId)
    {
        $this->scheduleId = $scheduleId;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'judul' => 'Jadwal Lab Baru',
            'pesan' => 'Ada jadwal lab baru yang harus Anda ikuti hari ini. Silakan lakukan check-in.',
            'schedule_id' => $this->scheduleId,
            'url' => url('/siswa/check-in/' . $this->scheduleId) // Langsung arahkan ke halaman check-in
        ];
    }
}