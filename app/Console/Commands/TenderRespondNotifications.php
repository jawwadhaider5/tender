<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TenderRespondNotifications extends Command
{
    protected $signature = 'tenderrespond:send-notifications';
    protected $description = 'Send email notifications for tender responding in 2 days';

    public function handle()
    {
        $targetDate = Carbon::now()->addDays(2)->toDateString();

        $tenderrespond = DB::table('tender_responds')
            ->whereDate('date', $targetDate)
            ->select('date', 'text', 'subject', 'assigned_user_id')
            ->get();

        if ($tenderrespond->isEmpty()) {
            $this->info('No Tender Responds found');
            return;
        }

        foreach ($tenderrespond as $tr) {
            $user = User::findOrFail($tr->assigned_user_id);
            Mail::to($user->email)->send(new \App\Mail\TenderRespondNotification($tr));
        }

        $this->info('Tender respond notifications sent to concern users');
    }
}
