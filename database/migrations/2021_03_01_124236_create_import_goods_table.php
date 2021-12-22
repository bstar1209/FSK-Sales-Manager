<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_goods', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('quote_id')->nullable();
            $table->string('user_code', 50)->nullable();
            $table->string('maker')->nullable();
            $table->string('katashiki')->nullable();
            $table->string('dc')->nullable();
            $table->string('rohs')->nullable();
            $table->string('code_send', 100)->nullable();
            $table->double('ship_quantity')->nullable();
            $table->string('type_money_ship', 100)->nullable();
            $table->double('unit_ship')->nullable();
            $table->double('price_ship')->nullable();
            $table->double('import_qty')->nullable();
            $table->double('import_unit_price')->nullable();
            $table->string('coo')->nullable();
            $table->string('in_tr')->nullable();
            $table->integer('import_status')->default(0);
            $table->integer('importKBN')->nullable()->default(0);
            $table->string('export_time', 100)->nullable();
            $table->string('invoice_code', 100)->nullable();
            $table->string('out_tr')->nullable();
            $table->string('export_code')->nullable();
            $table->integer('export_status')->default(0);
            $table->boolean('is_send_mail')->default(false);
            $table->boolean('is_export')->default(false);
            $table->date('import_date')->nullable();
            $table->date('export_date')->nullable();
            $table->date('expectShipDate')->nullable();
            $table->date('import_date_plan')->nullable();
            $table->date('send_date')->nullable();
            $table->date('cancel_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_goods');
    }
}
