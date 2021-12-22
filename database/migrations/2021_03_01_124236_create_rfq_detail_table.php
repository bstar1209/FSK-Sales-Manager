<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfqdetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('count_expect');
            $table->double('cost_expect')->nullable();
            $table->string('condition1', 100)->nullable();
            $table->string('condition2', 100)->nullable();
            $table->string('condition3', 100)->nullable();
            $table->string('message', 200)->nullable();
            $table->string('message_to_buy', 200)->nullable();
            $table->string('duration_estimation', 50)->nullable();
            $table->integer('buy_qty')->nullable();
            $table->integer('sale_qty')->nullable();
            $table->float('buy_cost', 10, 0)->nullable();
            $table->float('sale_cost', 10, 0)->nullable();
            $table->float('estimation_cost', 10, 0)->nullable();
            $table->float('buy_money', 10, 0)->nullable();
            $table->float('sale_money', 10, 0)->nullable();
            $table->float('shipping_fee', 10, 0)->nullable();
            $table->float('interest_total', 10, 0)->nullable();
            $table->string('buy_unit', 10)->nullable();
            $table->string('sale_unit', 10)->nullable();
            $table->string('sale_money_type', 3)->nullable();
            $table->string('buy_money_type', 3)->nullable();
            $table->string('condition_pay', 100)->nullable();
            $table->boolean('is_solved')->default(false);
            $table->boolean('is_delete')->default(false);
            $table->boolean('is_excute')->default(false);
            $table->boolean('is_estimate')->default(false);
            $table->boolean('is_view')->default(false);
            $table->date('est_date')->nullable();
            $table->date('close_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rfq_detail');
    }
}
