<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockInTransitEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_in_transits', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->integer('route_id');
          $table->integer('vehicle_id');
          $table->integer('product_id');
          $table->integer('quanity');
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
        Schema::dropIfExists('stock_in_transits');
    }
}
