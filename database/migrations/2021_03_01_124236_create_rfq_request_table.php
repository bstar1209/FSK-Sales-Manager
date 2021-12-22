<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfqRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_request', function (Blueprint $table) {
            $table->id();
            $table->integer('detail_id');
            $table->integer('user_id');
            $table->integer('supplier_id')->nullable();
            $table->string('maker', 50)->nullable();
            $table->string('katashiki', 50);
            $table->string('katashiki_not_spl', 500)->nullable();
            $table->double('quantity_aspiration');
            $table->double('count_aspiration');
            $table->double('price_aspiration')->nullable();
            $table->double('total')->nullable();
            $table->string('kbn', 50)->nullable();
            $table->string('kbn2', 200)->nullable();
            $table->string('comment', 200)->nullable();
            $table->string('dc', 45)->nullable();
            $table->string('rohs', 100)->nullable();
            $table->boolean('is_old_data')->default(false);
            $table->date('solved_date')->nullable();
            $table->date('cancel_date')->nullable();
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
        Schema::dropIfExists('rfq_request');
    }
}
