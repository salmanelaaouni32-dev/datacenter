<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservationCreated extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'user_name' => $this->reservation->user->name,
            'resource_name' => $this->reservation->resource->name,
            'message' => 'Nouvelle demande de réservation de ' . $this->reservation->user->name . ' pour ' . $this->reservation->resource->name,
            'action_url' => route('manager.dashboard'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle demande de réservation')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Une nouvelle demande de réservation a été créée.')
            ->line('Ressource : ' . $this->reservation->resource->name)
            ->line('Demandeur : ' . $this->reservation->user->name)
            ->action('Ouvrir le tableau de bord', route('manager.dashboard'));
    }
}
