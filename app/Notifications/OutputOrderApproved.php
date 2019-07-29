<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use App\OutputOrder;

class OutputOrderApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $outputOrderNumber;
    protected $outputOrderId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(OutputOrder $outputOrder)
    {
        $this->outputOrderNumber = $outputOrder->number;
        $this->outputOrderId = $outputOrder->id;
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
            'output_order_id' => $this->outputOrderId,
            'output_order_number' => $this->outputOrderNumber,
        ];
    }

    public function toBroadcast($notifiable)
    {

        return new BroadcastMessage([
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'output_order_id' => $this->outputOrderId,
                'output_order_number' => $this->outputOrderNumber,
            ],
        ]);
    }
}
