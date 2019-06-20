<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class Contract extends Notification implements ShouldQueue
{
    use Queueable;

    protected $contractId;
    protected $status;
    protected $number;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($contractId, $status, $number)
    {
        $this->contractId = $contractId;
        $this->status = $status;
        $this->number = $number;
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
            'contract_id' => $this->contractId,
            'status' => $this->status,
            'number' => $this->number,
        ];
    }

    public function toBroadcast($notifiable)
    {

        return new BroadcastMessage([
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'contract_id' => $this->contractId,
                'status' => $this->status,
                'number' => $this->number,
            ],
        ]);
    }
}
