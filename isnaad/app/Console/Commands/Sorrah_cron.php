<?php

namespace App\Console\Commands;

use App\constans;
use App\order;
use App\Helpers\Salla_helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\in_transit;
use App\deliverd_orders;

class Sorrah_cron extends Command
{
	use Salla_helper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sorrah:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this crone job for Sorrah update status';

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
       Log::error('Sorrah cron job');

       $orders = order::where([['store_id', '13'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
//dd( $orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {

                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                 $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }
}
