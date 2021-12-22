<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderheaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_header', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('supplier_id')->nullable();
            $table->integer('staff_id')->nullable();
            $table->integer('payment_cond_id')->nullable();
            $table->integer('tax_id');
            $table->string('maker', 50)->nullable();
            $table->string('cond_payment', 100)->nullable();
            $table->integer('type_cond_pay')->nullable();
            $table->string('order_no_by_customer', 100)->nullable();
            $table->string('sale_type_money', 50)->nullable();
            $table->string('type_money', 3)->nullable();
            $table->double('fee_shipping')->nullable();
            $table->double('fee_daibiki')->nullable();
            $table->string('code_invoice', 20)->nullable();
            $table->date('date_invoice')->nullable();
            $table->date('receive_order_date')->nullable();
            $table->date('expect_ship_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_header');
    }
}
