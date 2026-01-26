<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationUpdated extends Notification
{
    use Queueable;

    protected $reservation;
    protected $status; // 'approuvée', 'refusée', 'annulée'

    /**
     * Create a new notification instance.
     */
    public function __construct($reservation, $status)
    {
        $this->reservation = $reservation;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'resource_name' => $this->reservation->resource->name,
            'new_status' => $this->status,
            'message' => "Votre réservation pour " . $this->reservation->resource->name . " est maintenant " . $this->status . ".",
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mise à jour de votre réservation')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line("Votre réservation pour " . $this->reservation->resource->name . " est maintenant " . $this->status . ".")
            ->action('Voir mes réservations', route('internal.reservations'));
    }
}
