<?php

namespace App\Console\Commands;

use App\Classes\FDA;
use App\constans;
use App\order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FDA_cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FDA:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this crone job for FDA update status';

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
		Log::error('FDA cron job started');
        $constant= constans::where('name','FDA_offset')->first();
        $orders1 = order::where([['carrier', 'FDA'], ['active', '1'], ['order_status', 'inTransit'], ['processing_status', '0']])->get();
        $count= $orders1->count();

        $orders = order::where([['carrier', 'FDA'], ['active', '1'], ['order_status', 'inTransit'], ['processing_status', '0']])->offset($constant->value)->limit(50)->get();
     //  dd($orders->count());
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            $id = FDA::update_status($tracking_num, $order->id);
        }

        $constant->value = $constant->value +50;
       $constant->save();
        if($constant->value >= $count){
        $constant->value = 0;
        $constant->save();
        }
    }
}
