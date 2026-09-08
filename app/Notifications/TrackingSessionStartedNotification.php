<?php

namespace App\Notifications;


use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrackingSessionStartedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public User $driver,
        public Vehicle $vehicle
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Tracking Session Started',
            'message' => 'A driver has started a tracking session.',
            'driver' => [
                'id' => $this->driver->id,
                'name' => $this->driver->name,
            ],
            'vehicle' => [
                'id' => $this->vehicle->id,
                'name' => $this->vehicle->name,
                'plate_number' => $this->vehicle->plate_number,
            ],
        ];
    }
}
