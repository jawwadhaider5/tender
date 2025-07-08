<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ClientRespondNotifications extends Command
{
    protected $signature = 'clientrespond:send-notifications';
    protected $description = 'Send email notifications for client responding in 2 days';

    public function handle()
    {
        $targetDate = Carbon::now()->addDays(2)->toDateString();

        $clientrespond = DB::table('client_responds')
            ->whereDate('date', $targetDate)
            ->select('date', 'text', 'subject', 'assigned_user_id')
            ->get();

        if ($clientrespond->isEmpty()) {
            $this->info('No client found');
            return;
        }

        foreach ($clientrespond as $cr) {
            $user = User::findOrFail($cr->assigned_user_id);
            Mail::to($user->email)->send(new \App\Mail\ClientRespondNotification($cr));
        }

        $this->info('client respond notifications sent to concern users');
    }
}
