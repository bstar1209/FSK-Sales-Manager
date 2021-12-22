<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldInRfqDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfq_detail', function (Blueprint $table) {
            $table->boolean('is_send_cus')->default(false);
            $table->date('solved_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfq_detail', function (Blueprint $table) {
            $table->dropColumn(['is_send_cus', 'solved_date']);
        });
    }
}
