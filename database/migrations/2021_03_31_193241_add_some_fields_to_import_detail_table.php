<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsToImportDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_detail', function (Blueprint $table) {
            $table->integer('import_id');
            $table->integer('order_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_detail', function (Blueprint $table) {
            $table->dropColumn(['import_id', 'order_detail_id']);
        });
    }
}
