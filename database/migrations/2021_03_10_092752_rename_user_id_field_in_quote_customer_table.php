<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameUserIdFieldInQuoteCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_customer', function (Blueprint $table) {
            $table->renameColumn('user_id', 'customer_id');
            $table->renameColumn('vendor', 'supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_customer', function (Blueprint $table) {
            $table->renameColumn('customer_id', 'user_id');
            $table->renameColumn('supplier_id', 'vendor');
        });
    }
}
