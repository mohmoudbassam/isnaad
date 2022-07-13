<?php

namespace App\Console\Commands;

use App\Classes\Aymakan;
use App\constans;
use App\order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Aymakan_cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Aymakan:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this crone job for Aymakan update status';

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
        Log::error('Aymakan cron job started');
        $constant = constans::where('name', 'aymakan_offset')->first();
        $orders1 = order::where([['carrier', 'Aymakan'], ['active', '1'], ['order_status', 'inTransit'], ['processing_status', '0']])->get();
        $count = $orders1->count();
        $orders = order::where([['carrier', 'Aymakan'], ['active', '1'], ['order_status', 'inTransit'], ['processing_status', '0']])->offset($constant->value)->limit(50)->get();

        foreach ($orders as $order) {

            // $id =  Aymakan::update_status('404019', '21');
            $id =  Aymakan::update_status($order->tracking_number, $order->id);
        }
        $constant->value = $constant->value + 50;
        $constant->save();
        if ($constant->value >= $count) {
            $constant->value = 0;
            $constant->save();
        }
    }
}
