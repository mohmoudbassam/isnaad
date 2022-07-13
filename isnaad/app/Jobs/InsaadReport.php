<?php

namespace App\Jobs;

use App\Mail\CompleteExportedFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class InsaadReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $user;
    public $filePath;

    public function __construct($user,$filePath)
    {
        $this->user = $user;
        $this->filePath = $filePath;
    }

    public function handle()
    {
        Mail::to($this->user)->send(new CompleteExportedFile($this->filePath));
    }
}
