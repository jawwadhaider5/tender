<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; 
use Illuminate\Mail\Mailable; 
use Illuminate\Queue\SerializesModels;

class TenderRespondNotification extends Mailable
{
    use Queueable, SerializesModels; 

    public $tender_respond;

    public function __construct($tender_respond)
    {
        $this->tender_respond = $tender_respond;
    }

    public function build()
    {
        return $this->subject('Upcoming Tender Responds')
                    ->view('emails.tender_respond');
    }
}
