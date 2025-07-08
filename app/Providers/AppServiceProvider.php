<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('path.public', function() {
        //     return realpath(base_path().'/../public_html');
        //     });
    }
 
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view) { 
            $user = auth()->user();
            $noti = [];
            $unoti = [];
            if ($user) {
                $unoti = $user->unreadnotifications;
                $noti = $user->notifications; 
            }
            $view->with('unreadnotifications',$unoti);
            $view->with('sellsnotification',$noti); 
        }); 


        //Blade directive to format number into required format.
        Blade::directive('num_format', function ($expression) {
            return "number_format($expression, 2,'.', '')";
        });

    }
}
