<?php

namespace App\Console\Commands;

use App\Classes\AramexAPI;
use App\constans;
use App\order;
use App\statment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class netBlanceCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netBlance:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this cron for update invoices to paid';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       
       $statments= statment::query();
        $statments=  $statments->where('paid','0')->whereMonth('created_at','>' ,'3')->get();
        
        foreach($statments as $statment){
            if($statment->net_blance==0){
                $statment->update([
                   'paid'=>1
                ]);
            }
        }


    }
}
