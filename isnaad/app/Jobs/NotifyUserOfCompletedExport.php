<?php

namespace App\Jobs;

use App\Notifications\ExportReady;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUserOfCompletedExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use Queueable, SerializesModels;

    public $user;

    public function __construct( $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $this->user->notify(new ExportReady());
    }
}
