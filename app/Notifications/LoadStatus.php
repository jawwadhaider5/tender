<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;



class LoadStatus extends Notification
{
    use Queueable;

    private $sell;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sell)
    {
        //
        $this->sell = $sell;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    // ->line('The introduction to the notification.')
                    // ->action('Notification Action', url('/'))
                    // ->line('Thank you for using our application!');


                    // return (new MailMessage)                    
                    ->name($this->loadData['title'])
                    ->line($this->loadData['body'])
                    ->action($this->loadData['buttonText'], $this->loadData['loadUrl'])
                    ->line($this->invoiceData['thanks']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        

        return [
            // 'loaded_date' => $this->sell['loaded_date'],
            'data' => $this->sell,

        ];

    }
}
