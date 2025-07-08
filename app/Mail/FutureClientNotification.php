<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FutureClientNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $futureclient;

    public function __construct($futureclient)
    {
        $this->futureclient = $futureclient;
    }

    public function build()
    {
        return $this->subject('Upcoming Tender Closures')
                    ->view('emails.futureclient');
    }
}
