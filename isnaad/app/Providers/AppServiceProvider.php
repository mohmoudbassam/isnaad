<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
          $this->app->bind('path.public', function() {
        realpath(base_path().'/../public_html');
    });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         date_default_timezone_set('Asia/Riyadh');
    }
}
