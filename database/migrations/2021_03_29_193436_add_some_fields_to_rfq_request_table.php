<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsToRfqRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfq_request', function (Blueprint $table) {
            $table->string('condition1', 100)->nullable();
            $table->string('condition2', 100)->nullable();
            $table->string('condition3', 100)->nullable();
            $table->boolean('is_solved')->default(false);
            $table->boolean('is_cancel')->default(false);
            $table->boolean('is_send_cus')->default(false);
            $table->boolean('child_index')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfq_request', function (Blueprint $table) {
            $table->dropColumn(['condition1', 'condition2', 'condition3', 'is_solved', 'is_cancel', 'is_send_cus', 'child_index']);
        });
    }
}
