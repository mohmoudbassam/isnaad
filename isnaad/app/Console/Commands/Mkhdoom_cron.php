<?php

namespace App\Console\Commands;

use App\Classes\Mkhdoom;
use App\constans;
use App\order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Mkhdoom_cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Mkhdoom:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this crone job for Mkhdoom update status';

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
        Log::error('Mkhdoom cron job started');
        $constant= constans::where('name','mkhdoom_offset')->first();
        $orders1 = order::where([['carrier', 'Mkhdoom'], ['active', '1'], ['order_status', 'inTransit'], ['processing_status', '0']])->get();
        $count= $orders1->count();
//dd($count);
        $orders = order::where([['carrier', 'Mkhdoom'], ['active', '1'], ['order_status', 'inTransit'], ['processing_status', '0']])->offset($constant->value)->limit(50)->get();
             //$orders = order::where([['carrier', 'Mkhdoom'], ['active', '1'], ['order_status', 'Delivered'], ['processing_status', '0']])->whereBetween('created_at',['2020-04-01','2021-03-01'])->offset($constant->value)->limit(50)->get();

        //$orders = order::where([['carrier', 'Mkhdoom'], ['active', '1'], ['order_status', 'Delivered'], ['processing_status', '0']])->offset($constant->value)->limit(50)->get();


        // $orders = order::where([['id','120378']])->get();
        //  dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            $id = Mkhdoom::update_status($tracking_num, $order->id);
        }
       
        $constant->value = $constant->value +50;
        $constant->save();
         
        if($constant->value >= $count){
        $constant->value = 0;
        $constant->save();
        }
        
    }
}
