<?php

namespace App\Console\Commands;

use App\Classes\AramexAPI;
use App\constans;
use App\order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class aramex_cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aramex:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this crone job for aramex update status';

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
        Log::error('aramex cron job started');
        // AramexAPI::updatestatus(45762223706,27);
        $constant= constans::where('name','aramex_offset')->first();
        $orders1 = order::where([['carrier', 'Aramex'], ['active', '1'],['processing_status','0'],['order_status','inTransit']])->get();
        $count= $orders1->count();

        $orders = order::where([['carrier', 'Aramex'], ['active', '1'],['processing_status','0'],['order_status','inTransit']])->offset($constant->value)->limit(50)->get();
        // $orders = order::Where([['carrier', 'Aramex'], ['active', '1'], ['order_status', 'Data Uplouded'],['processing_status','0']])->offset($constant->value)->limit(50)->get();


        //   $orders = order::where([['tracking_number','32520133822']])->get();
        //  dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            $id = AramexAPI::update_status($tracking_num, $order->id);
        }

        $constant->value = $constant->value +50;
        $constant->save();

        if($constant->value >= $count){
            $constant->value = 0;
            $constant->save();
        }

        if($constant->value == 500){
            Log::error('aramex works');
        }
    }
}
