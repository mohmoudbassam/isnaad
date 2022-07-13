<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTokenAndExpiersAtToCarriers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carriers', function (Blueprint $table) {
            $table->string('token')->nullable();
            $table->integer('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carriers', function (Blueprint $table) {
            $table->dropColumn('token');
            $table->dropColumn('expires_at');
        });
    }
}
