<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('carrier');
            $table->string('ship_method');
            $table->string('tracking_number');
            $table->integer('cod_amount')->default(0);
            $table->string('awb_url')->nullable();
            $table->integer('store_id');
            $table->string('shipping_number');
            $table->string('order_number');
            $table->double('shipping_charge');
            $table->double('cod_charge')->default(0);
            $table->string('processing_status');
            $table->string('delivery_status')->nullable();
            $table->date('processing_date')->nullable();
            $table->date('shipping_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->double('weight')->default(0);
            $table->string('description')->nullable();
            $table->string('fname');
            $table->string('lname')->nullable();
            $table->string('country');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
