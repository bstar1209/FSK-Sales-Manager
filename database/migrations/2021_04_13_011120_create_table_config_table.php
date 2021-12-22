<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_config', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('table_name', 50);
            $table->text('column_names')->nullable();
            $table->text('column_info')->nullable();
            $table->text('column_order_info')->nullable();
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
        Schema::dropIfExists('table_config');
    }
}
