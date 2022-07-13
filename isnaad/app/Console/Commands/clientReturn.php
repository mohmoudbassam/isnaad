<?php

namespace App\Console\Commands;

use App\Classes\AramexAPI;
use App\constans;
use App\Models\isnaad_return;
use Illuminate\Console\Command;

class clientReturn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:clientReturn';
    public static $PATH = 'App\Classes\\';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $constant = constans::where('name', 'client_return_offset')->first();
       $orders= isnaad_return::query()->whereHas('order',function ($q){
           $q->where('order_status','!=','Delivered');
       })->offset($constant->value)->limit(50)->get();

       foreach($orders as $order){
           if($order->carrier=='Aramex'){
               AramexAPI::update_status($order->traking_number,$order->order->id);
           }else{

               $class_called = self::$PATH . $order->carrier;
               $class_called::update_status($order->traking_number,$order->order->id);
           }
       }
        $constant->value = $constant->value + 50;
        $constant->save();
    }
}
