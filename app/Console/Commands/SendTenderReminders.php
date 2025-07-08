<?php

namespace App\Console\Commands;

use App\Models\Tender;
use App\Models\User;
use App\Notifications\TenderRespond;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTenderReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenders:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for tenders closing in 3 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dat =  Carbon::now()->addDays(3)->toDateString();  
        $tender_respond = TenderRespond::whereDate('date', $dat)->get();
        foreach ($tender_respond as $tender) { 
            $dt = $tender->date->format('M, d Y H:i:s A'); 
            User::find($tender->assigned_user_id)->notify(new TenderRespond($dt, $tender->responded_by->name, $tender->subject, $tender->text));
        }

        $this->info('Tender reminders sent successfully.');
    }
}
