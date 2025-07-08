<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SendFutureClientNotifications extends Command
{
    protected $signature = 'futureclient:send-notifications';
    protected $description = 'Send email notifications for future client closing in 2 days';

    public function handle()
    {
        $targetDate = Carbon::now()->addDays(2)->toDateString();

        $futureclient = DB::table('future_clients')
            ->whereDate('start_date', $targetDate)
            ->get();

        if ($futureclient->isEmpty()) {
            $this->info('No future client found starting in 2 days.');
            return;
        }

        $users = User::all();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new \App\Mail\FutureClientNotification($futureclient));
        }

        $this->info('Tender notifications sent to all users.');
    }
}
