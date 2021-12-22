<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_log', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('search_count')->default(0);     
            $table->integer('ans_count')->default(0);
            $table->integer('res_count')->default(0);
            $table->integer('order_Qty')->default(0);
            $table->float('order_money', 10, 0)->default(0);
            $table->integer('ans_quote_cus')->default(0);
            $table->date('request_date')->nullable();
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
        Schema::dropIfExists('customer_log');
    }
}
