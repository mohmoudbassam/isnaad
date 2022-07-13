<?php

namespace App\Console\Commands;

use App\Classes\BARQ;
use App\Barq_order_id;
use App\constans;
use App\order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BARQ_cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BARQ:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this crone job for BARQ update status';

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
        Log::error('BARQ cron job started');
        $constant = constans::where('name', 'barq_offset')->first();
        $orders1 = order::where([['carrier', 'BARQ'], ['active', '1'], ['order_status', 'inTransit'], ['processing_status', '0']])->get();
        $count = $orders1->count();
     //   $orders = order::where([['carrier', 'BARQ'], ['active', '1'], ['order_status', 'inTransit'], ['processing_status', '0']])->offset($constant->value)->limit(100)->get();
$orders = order::where([['carrier', 'BARQ'], ['active', '1'], ['order_status', 'Delivered'], ['processing_status', '0']])->whereBetween('created_at',['2020-04-01','2021-03-01'])->offset($constant->value)->limit(50)->get();
        foreach ($orders as $order) {
            $barq_id = Barq_order_id::where([['ship_no', $order->shipping_number]])->first();
            if(!isset($barq_id->barq_id)){
                 Log::error('BARQ id not found: '.$order->shipping_number);
            }
            $barq_order_id = $barq_id->barq_id;
            Barq::update_status($order->shipping_number, $barq_order_id);
        }
        $constant->value = $constant->value + 50;
        $constant->save();
        /*
        if ($constant->value >= $count) {
           
            $constant->value = 0;
            $constant->save();
        }
        */
    }
}
