<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFieldInOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_detail', function (Blueprint $table) {
            $table->renameColumn('katasiki', 'katashiki');
        });

        Schema::table('est_place_buy', function (Blueprint $table) {
            $table->renameColumn('katasiki_est', 'katashiki_est');
        });

        Schema::table('import_detail', function (Blueprint $table) {
            $table->renameColumn('katasiki', 'katashiki');
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->renameColumn('katasiki', 'katashiki');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_detail', function (Blueprint $table) {
            $table->renameColumn('katashiki', 'katasiki');
        });

        Schema::table('est_place_buy', function (Blueprint $table) {
            $table->renameColumn('katashiki_est', 'katasiki_est');
        });

        Schema::table('import_detail', function (Blueprint $table) {
            $table->renameColumn('katashiki', 'katasiki');
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->renameColumn('katashiki', 'katasiki');
        });
    }
}
