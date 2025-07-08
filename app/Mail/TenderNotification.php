<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $tenders;

    public function __construct($tenders)
    {
        $this->tenders = $tenders;
    }

    public function build()
    {
        return $this->subject('Upcoming Tender Closures')
                    ->view('emails.tenders');
    }
}
