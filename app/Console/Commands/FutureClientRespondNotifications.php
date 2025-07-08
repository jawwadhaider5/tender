<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FutureClientRespondNotifications extends Command
{
    protected $signature = 'futureclientrespond:send-notifications';
    protected $description = 'Send email notifications for future client responding in 2 days';

    public function handle()
    {
        $targetDate = Carbon::now()->addDays(2)->toDateString();

        $futureclientrespond = DB::table('future_client_responds')
            ->whereDate('date', $targetDate)
            ->select('date', 'text', 'subject', 'assigned_user_id')
            ->get();

        if ($futureclientrespond->isEmpty()) {
            $this->info('No future client responds found');
            return;
        }

        foreach ($futureclientrespond as $fcr) {
            $user = User::findOrFail($fcr->assigned_user_id);
            Mail::to($user->email)->send(new \App\Mail\FutureClientRespondNotification($fcr));
        }

        $this->info('Future client respond notifications sent to concern users');
    }
}
