<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenderRespond extends Notification
{
    use Queueable;

    protected $deadline;
    protected $user;
    protected $topic;
    protected $comment;

    public function __construct($deadline,$user,$topic,$comment)
    {
        $this->deadline=$deadline;
        $this->user=$user;
        $this->topic=$topic;
        $this->comment=$comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
                    ->greeting('You get responds notification on "'.$this->topic.'"')
                    ->line('Deadline is : '. $this->deadline)
                    ->action('Go to website', url('/tenders'))
                    ->line('Responds is: '.$this->comment)
                    ->line('Responds By: '.$this->user);
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
            'topic' => $this->topic,
            'comment' => $this->comment,
            'deadline' =>$this->deadline,
            'responded_by' => $this->user
        ];
    }
}
