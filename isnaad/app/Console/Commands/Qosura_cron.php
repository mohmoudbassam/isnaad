<?php

namespace App\Console\Commands;

use App\constans;
use App\order;
use App\Helpers\Salla_helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\in_transit;
use App\deliverd_orders;

class Qosura_cron extends Command
{
	use Salla_helper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Qosura:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this crone job for Qosura update status';

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
        
       Log::error('Qosura cron job');

        $orders = order::where([['store_id', '68'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
   
          
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                        Log::error('suc Qosura orNum inTransit' . $order->order_number);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                  $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    if($order->order_status == 'Delivered'){
                    Log::error('suc Qosura orNum Delivered' . $order->order_number);
                    }else{
                    Log::error('suc Qosura orNum Returned' . $order->order_number);
                    }
                     
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }
}
