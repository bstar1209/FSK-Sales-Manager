<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_log', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id');
            $table->integer('est_req_count')->default(0);
            $table->integer('ans_est_count')->default(0);
            $table->integer('ans_emp_count')->default(0);
            $table->integer('ship_order_count')->default(0);
            $table->float('ship_order_money', 10, 0)->default(0);
            $table->integer('return_time')->default(0);
            $table->integer('cancel_OP_Qty')->default(0);
            $table->date('request_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplierlog');
    }
}
