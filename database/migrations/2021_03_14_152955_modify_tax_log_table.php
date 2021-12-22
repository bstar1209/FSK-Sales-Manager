<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTaxLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_log', function (Blueprint $table) {
            $table->dropColumn(['tax_id', 'apply_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_log', function (Blueprint $table) {
            $table->integer('tax_id');
            $table->date('apply_date')->nullable();
        });
    }
}
