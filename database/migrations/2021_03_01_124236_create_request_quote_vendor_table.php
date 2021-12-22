<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestQuoteVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_quote_vendor', function (Blueprint $table) {
            $table->id();
            $table->integer('rfq_request_id');
            $table->integer('rfq_request_child_id');
            $table->string('vendor', 50);
            $table->string('maker')->nullable();
            $table->string('katashiki', 255)->nullable();
            $table->string('katashiki_not_spl', 500)->nullable();
            $table->double('quantity_buy')->nullable();
            $table->string('unit_buy', 50)->nullable();
            $table->string('type_money_buy', 45)->nullable();
            $table->double('unit_price_buy')->nullable();
            $table->string('dc', 45)->nullable();
            $table->string('kbn2', 200)->nullable();
            $table->string('rohs', 45)->nullable();
            $table->string('code_quote', 100)->nullable();
            $table->double('fee_shipping')->nullable();
            $table->date('date_quote')->nullable();
            $table->text('comment_business')->nullable();
            $table->boolean('is_sendmail')->default(false);
            $table->boolean('is_send_est')->default(false);
            $table->boolean('is_received_quote')->default(false);
            $table->text('fee_ship2')->nullable();
            $table->string('deadline_buy_vendor', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_quote_vendor');
    }
}
