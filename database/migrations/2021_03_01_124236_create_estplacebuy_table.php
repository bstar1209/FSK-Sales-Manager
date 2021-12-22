<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstplacebuyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('est_place_buy', function (Blueprint $table) {
            $table->id();
            $table->integer('rfq_id');
            $table->string('place_buy', 100)->nullable();
            $table->string('maker', 50)->nullable();
            $table->string('type_money', 10)->nullable();
            $table->double('cost_buy')->nullable();
            $table->string('dc', 50)->nullable();
            $table->string('rohs', 50)->nullable();
            $table->string('time_delivery', 50)->nullable();
            $table->string('fee_note', 100)->nullable();
            $table->string('code_est_buy', 50)->nullable();
            $table->boolean('is_send_est')->default(false);
            $table->float('shipping_fee', 10, 0)->nullable();
            $table->text('note_fee')->nullable();
            $table->string('katasiki_est', 100)->nullable();
            $table->date('est_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('est_place_buy');
    }
}
