<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\aramex_cron::class,
        \App\Console\Commands\Smsa_cron::class,
        \App\Console\Commands\Mkhdoom_cron::class,
        \App\Console\Commands\Wadha_cron::class,
        \App\Console\Commands\FDA_cron::class,
        // \App\Console\Commands\Tamex_cron::class,
        \App\Console\Commands\Sidraoil_cron::class,
        \App\Console\Commands\Sorrah_cron::class,
        \App\Console\Commands\wixana_cron::class,
        \App\Console\Commands\rosmond_cron::class,
        \App\Console\Commands\rahig_cron::class,
        \App\Console\Commands\bedro_cron::class,
        \App\Console\Commands\Golden_Occasion_cron::class,
        \App\Console\Commands\Coffee_secrets_cron::class,
        \App\Console\Commands\wareedmedical_cron::class,
        \App\Console\Commands\Sadatalbukhur_cron::class,
        \App\Console\Commands\saif_nakhla_cron::class,
        \App\Console\Commands\BARQ_cron::class,
        \App\Console\Commands\scoffee_cron::class,
        \App\Console\Commands\Aymakan_cron::class,
        \App\Console\Commands\blanco_cron::class,
        \App\Console\Commands\Lastpoint_cron::class,
        \App\Console\Commands\Seenglasses_cron::class,
        \App\Console\Commands\Sukkari_cron::class,
        \App\Console\Commands\netBlanceCheck::class,
        \App\Console\Commands\HG_cron::class,
        \App\Console\Commands\Sign_cron::class,
        \App\Console\Commands\Hrof_cron::class,
        \App\Console\Commands\Scarf_cron::class,
        \App\Console\Commands\Naqel_cron::class,
        \App\Console\Commands\Kudhha_cron::class,
        \App\Console\Commands\clientReturn::class,
        \App\Console\Commands\Qosura_cron::class,
        \App\Console\Commands\Selene_cron::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('aramex:cron')->everyTenMinutes();
        $schedule->command('Smsa:cron')->everyFifteenMinutes();
        $schedule->command('Mkhdoom:cron')->everyTenMinutes();
        $schedule->command('Wadha:cron')->everyFifteenMinutes();
        $schedule->command('FDA:cron')->everyFifteenMinutes();
        $schedule->command('Kudhha:cron')->everyFifteenMinutes();
        //$schedule->command('Tamex:cron')->everyFifteenMinutes();
        $schedule->command('sidra_oil:cron')->hourly();
        $schedule->command('Sorrah:cron')->everyThirtyMinutes();
        $schedule->command('wixana:cron')->everyThirtyMinutes();
        $schedule->command('rosmond:cron')->hourly();
        $schedule->command('rahig:cron')->hourly();
        $schedule->command('bedro:cron')->hourly();
        $schedule->command('Golden_Occasion:cron')->hourly();
        $schedule->command('Coffee_secrets:cron')->hourly();
        $schedule->command('wareedmedical:cron')->hourly();
        $schedule->command('Sadatalbukhur:cron')->hourly();
        $schedule->command('saif_nakhla:cron')->hourly();
        //$schedule->command('BARQ:cron')->everyFifteenMinutes();
        $schedule->command('scoffee:cron')->hourly();
        $schedule->command('Aymakan:cron')->everyFifteenMinutes();
        $schedule->command('blanco:cron')->hourly();
        $schedule->command('Lastpoint:cron')->hourly();
        $schedule->command('Seenglasses:cron')->hourly();
        $schedule->command('Sukkari:cron')->hourly();
        $schedule->command('netBlance:cron')->hourly();
        $schedule->command('HG:cron')->hourly();
        $schedule->command('Sign:cron')->hourly();
        $schedule->command('Hrof:cron')->hourly();
        $schedule->command('Scarf:cron')->hourly();
        // $schedule->command('Naqel:cron')->everyFifteenMinutes();
        //  $schedule->command('command:clientReturn')->hourly();

        $schedule->command('Qosura:cron')->hourly();
        $schedule->command('Selene:cron')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {

        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
