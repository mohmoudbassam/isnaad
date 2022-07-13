<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarrierCityIdToCarrierCities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carrier_cities', function (Blueprint $table) {
            $table->string('carrier_city_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carrier_cities', function (Blueprint $table) {
            $table->dropColumn('carrier_city_id');
        });
    }
}
