<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportdetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('katasiki', 100)->nullable();
            $table->string('maker', 50)->nullable();
            $table->string('DC', 50)->nullable();
            $table->integer('import_KBN')->nullable();
            $table->string('condition2', 100)->nullable();
            $table->string('condition3', 100)->nullable();
            $table->integer('import_Qty')->nullable();
            $table->float('import_cost', 10, 0)->nullable();
            $table->float('import_money', 10, 0)->nullable();
            $table->integer('export_qty')->nullable();
            $table->integer('export_cost')->nullable();
            $table->string('no_payment', 50)->nullable();
            $table->string('no_bill', 50)->nullable();
            $table->string('ship_fee', 50)->nullable();
            $table->string('cash_on_delivery_fee', 50)->nullable();
            $table->date('payment_rqst_date')->nullable();
            $table->date('cancel_date')->nullable();
            $table->date('cancel_shipment_date')->nullable();
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
        Schema::dropIfExists('importdetail');
    }
}
