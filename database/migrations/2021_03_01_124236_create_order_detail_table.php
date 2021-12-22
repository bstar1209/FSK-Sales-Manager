<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderdetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('order_header_id');
            $table->integer('request_address_id');
            $table->integer('send_address_id');
            $table->integer('supplier_id');
            $table->integer('tax_id');
            $table->integer('quote_id');
            $table->string('katasiki', 100)->nullable();
            $table->string('katashiki_not_spl', 50)->nullable();
            $table->string('maker', 50)->nullable();
            $table->string('dc', 50)->nullable();
            $table->string('kubun', 10)->nullable();
            $table->string('kubun2', 200)->nullable();
            $table->string('condition1', 100)->nullable();
            $table->string('condition2', 100)->nullable();
            $table->string('condition3', 100)->nullable();
            $table->string('message')->nullable();
            $table->double('sale_qty')->nullable();
            $table->double('buy_qty')->nullable();
            $table->float('sale_cost', 10, 0)->nullable();
            $table->float('buy_cost', 10, 0)->nullable();
            $table->float('sale_money', 10, 0)->nullable();
            $table->float('buy_money', 10, 0)->nullable();
            $table->string('sale_unit', 10)->nullable();
            $table->string('buy_unit', 10)->nullable();
            $table->float('interest_total', 10, 0)->nullable();
            $table->integer('ship_to')->nullable();
            $table->integer('ship_by')->nullable();
            $table->integer('order_KBN')->nullable();
            $table->float('fee_daibiki', 10, 0)->nullable();
            $table->float('fee_shipping', 10, 0)->nullable();
            $table->string('order_no_by_customer', 100);
            $table->integer('order_status')->default(0);
            $table->integer('status_ship')->default(0);
            $table->string('code_send', 6)->nullable();
            $table->text('refer_vendor')->nullable();
            $table->integer('ship_quantity')->nullable();
            $table->string('type_money_ship', 10)->nullable();
            $table->double('unit_buy_ship')->nullable();
            $table->double('price_ship')->nullable();
            $table->integer('sent_ship')->nullable();
            $table->integer('solved')->default(0);
            $table->text('refer_order')->nullable();
            $table->date('cancel_date_vendor')->nullable();
            $table->date('receive_order_aprrove_date')->nullable();
            $table->date('ship_order_date')->nullable();
            $table->date('cal_order_date')->nullable();
            $table->date('cal_shipOrderdate')->nullable();
            $table->date('est_date')->nullable();
            $table->date('plan_send_date')->nullable();
            $table->date('ship_date')->nullable();
            $table->date('deadline_send')->nullable();
            $table->date('send_date')->nullable();
            $table->date('cancel_date_user')->nullable();
            $table->date('import_date_plan')->nullable();
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
        Schema::dropIfExists('order_detail');
    }
}
