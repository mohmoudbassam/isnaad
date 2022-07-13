<?php

namespace App\Jobs;

use App\Helpers\carrier_charge;
use App\order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class updateCarrierCharge implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels , carrier_charge;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
     
        foreach ($this->data as $or) {


         
            $carrier_charge=$this->carrier_charge($or->carrier, $or);
         //   dd($carrier_charge,$or);
            //  $order->carrier_charge= $carrier_charge;
            //  dd($order);
            order::where([['active', 1], ['shipping_number', $or->shipping_number]])->update(
                [
                    'carrier_charge' =>$carrier_charge[0],
                    'extraWeight'=>$carrier_charge[1],
                    'extraPrice'=>$carrier_charge[2]
                ]);
    }

}}
