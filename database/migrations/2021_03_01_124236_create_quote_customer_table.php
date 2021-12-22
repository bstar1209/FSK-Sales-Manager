<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_customer', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->integer('request_vendor_id')->nullable();
            $table->string('quote_code', 100)->nullable();
            $table->string('user_res', 100)->nullable();
            $table->string('maker')->nullable();
            $table->string('katashiki')->nullable();
            $table->string('katashiki_not_spl', 50)->nullable();
            $table->string('dc')->nullable();
            $table->string('rohs')->nullable();
            $table->string('kbn2', 200)->nullable();
            $table->string('country', 100)->nullable();
            $table->integer('count_predict')->nullable();
            $table->string('quote_prefer')->nullable();
            $table->string('deadline_quote')->nullable();
            $table->string('vendor', 100)->nullable();
            $table->integer('buy_quantity')->nullable();
            $table->string('unit_buy', 100)->nullable();
            $table->string('type_money_buy', 100)->nullable();
            $table->double('unit_price_buy')->nullable();
            $table->double('money_buy')->nullable();
            $table->double('fee_shipping')->default(0);
            $table->double('rate_profit')->nullable();
            $table->double('price_quote')->nullable();
            $table->integer('sell_quantity')->nullable();
            $table->string('unit_sell', 100)->nullable();
            $table->string('type_money_sell', 100)->nullable();
            $table->double('unit_price_sell')->nullable();
            $table->double('money_sell')->nullable();
            $table->string('cond_payment')->nullable();
            $table->text('comment_bus')->nullable();
            $table->string('rank_quote', 100)->nullable();
            $table->string('profit', 100)->nullable();
            $table->double('unit_price_second')->nullable();
            $table->double('rate_profit_second')->nullable();
            $table->integer('sell_quantity_second')->nullable();
            $table->double('money_sell_second')->nullable();
            $table->boolean('is_cancel')->default(false);
            $table->boolean('is_solved')->default(false);
            $table->boolean('is_sendmail')->default(false);
            $table->boolean('is_delete')->default(false);
            $table->boolean('is_request')->default(false);
            $table->boolean('is_order')->default(false);
            $table->boolean('is_together')->default(false);
            $table->date('quote_date')->nullable();
            $table->date('date_send')->nullable();
            $table->date('receive_date')->nullable();
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
        Schema::dropIfExists('quote_customer');
    }
}
