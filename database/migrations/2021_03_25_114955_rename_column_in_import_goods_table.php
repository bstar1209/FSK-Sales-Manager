<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnInImportGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_goods', function (Blueprint $table) {
            $table->renameColumn('expectShipDate', 'expect_ship_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_goods', function (Blueprint $table) {
            $table->renameColumn('expect_ship_date', 'expectShipDate');
        });
    }
}
