<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SendTenderNotifications extends Command
{
    protected $signature = 'tenders:send-notifications';
    protected $description = 'Send email notifications for tenders closing in 2 days';

    public function handle()
    {
        $targetDate = Carbon::now()->addDays(2)->toDateString();

        $tenders = DB::table('tenders')
            ->whereDate('close_date', $targetDate)
            ->get();

        if ($tenders->isEmpty()) {
            $this->info('No tenders found closing in 2 days.');
            return;
        }

        $users = User::all();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new \App\Mail\TenderNotification($tenders));
        }

        $this->info('Tender notifications sent to all users.');
    }
}
