<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class OutputOrder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $goodDeliveryId;
    protected $outputOrderNumber;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($outputOrderNumber, $goodDeliveryId)
    {
        $this->outputOrderNumber = $outputOrderNumber;
        $this->goodDeliveryId = $goodDeliveryId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
//    public function toMail($notifiable)
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'good_delivery_id' => $this->goodDeliveryId,
            'output_order_number' => $this->outputOrderNumber,
        ];
    }

    public function toBroadcast($notifiable)
    {

        return new BroadcastMessage([
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'good_delivery_id' => $this->goodDeliveryId,
                'output_order_number' => $this->outputOrderNumber,
            ],
        ]);
    }
}
