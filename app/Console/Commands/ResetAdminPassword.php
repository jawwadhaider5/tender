<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password';
    protected $description = 'Reset the admin password';

    public function handle()
    {
        $user = User::where('email', 'superadmin@gmail.com')->first();
        
        if (!$user) {
            $this->error('Admin user not found!');
            return;
        }

        $password = 'Password!123';
        $user->password = Hash::make($password);
        $user->save();

        $this->info('Admin password has been reset successfully!');
        $this->info('Email: superadmin@gmail.com');
        $this->info('Password: ' . $password);
    }
} 