<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; 
use Illuminate\Mail\Mailable; 
use Illuminate\Queue\SerializesModels;

class FutureClientRespondNotification extends Mailable
{
    use Queueable, SerializesModels;

    use Queueable, SerializesModels;

    public $future_client_respond;

    public function __construct($future_client_respond)
    {
        $this->future_client_respond = $future_client_respond;
    }

    public function build()
    {
        return $this->subject('Upcoming Future Client Responds')
                    ->view('emails.future_client_respond');
    }
}
