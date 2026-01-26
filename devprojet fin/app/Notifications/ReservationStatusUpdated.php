<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservationStatusUpdated extends Notification
{
    use Queueable;

    public $reservation;
    public $status;

    public function __construct(Reservation $reservation, $status)
    {
        $this->reservation = $reservation;
        $this->status = $status;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $message = ($this->status === 'approved') 
            ? 'Votre réservation pour ' . $this->reservation->resource->name . ' a été approuvée.'
            : 'Votre réservation pour ' . $this->reservation->resource->name . ' a été refusée.';

        return [
            'reservation_id' => $this->reservation->id,
            'resource_name' => $this->reservation->resource->name,
            'status' => $this->status,
            'message' => $message,
            'action_url' => route('internal.reservations'),
        ];
    }
}
