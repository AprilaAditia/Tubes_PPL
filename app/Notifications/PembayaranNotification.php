<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PembayaranNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $pembayaran;
    public function __construct($pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())->line('The introduction to the notification.')->action('Notification Action', url('/'))->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tagihan_id' => $this->pembayaran->tagihan_id,
            'wali_id' => $this->pembayaran->wali_id,
            'pembayaran_id' => $this->pembayaran->id,
            'title' => 'Pembayaran Tagihan',
            'messages' => $this->pembayaran->wali->name . ' Melakukan Pembayaran Tagihan.',
            'url' => route('pembayaran.show', $this->pembayaran->id),
        ];
    }

   
}