<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatelogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('type_money', 3)->nullable();
            $table->float('sale_rate', 10, 0)->nullable();
            $table->float('buy_rate', 10, 0)->nullable();
            $table->timestamp('register_date')->nullable();
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
        Schema::dropIfExists('rate_log');
    }
}
