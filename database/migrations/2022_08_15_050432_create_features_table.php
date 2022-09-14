<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('coin_name');
            $table->enum('type', ['SHORT', 'LONG']);
            $table->decimal('entry1', $precision = 10, $scale = 5);
            $table->decimal('entry2', $precision = 10, $scale = 5);
            $table->decimal('entry3', $precision = 10, $scale = 5);
            $table->decimal('target1', $precision = 10, $scale = 5);
            $table->decimal('target2', $precision = 10, $scale = 5);
            $table->decimal('target3', $precision = 10, $scale = 5);
            $table->decimal('stop_loss', $precision = 10, $scale = 5);
            $table->decimal('avg_price', $precision = 10, $scale = 5);
            $table->decimal('usdt_pnl', $precision = 10, $scale = 5);
            $table->string('status');
            $table->string('docdate');
            $table->text('description');
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
        Schema::dropIfExists('features');
    }
}
