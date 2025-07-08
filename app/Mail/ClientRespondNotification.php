<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; 
use Illuminate\Mail\Mailable; 
use Illuminate\Queue\SerializesModels;

class ClientRespondNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $client_respond;

    public function __construct($client_respond)
    {
        $this->client_respond = $client_respond;
    }

    public function build()
    {
        return $this->subject('Upcoming Client Responds')
                    ->view('emails.client_respond');
    }
}
