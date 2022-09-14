<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('symbol')->nullable();
            $table->string('asset');
            $table->string('income_type');
            $table->decimal('income', $precision = 17, $scale = 8);
            $table->string('time');
            $table->string('info');
            $table->string('tran_id');
            $table->string('trade_id');
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
        Schema::dropIfExists('order_histories');
    }
}
