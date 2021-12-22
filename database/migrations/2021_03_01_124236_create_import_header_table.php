<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportheaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_header', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('type_money', 100)->nullable();
            $table->string('Co', 50)->nullable();
            $table->string('in_TR', 50)->nullable();
            $table->string('out_TR', 50)->nullable();
            $table->date('import_date')->nullable();
            $table->date('export_date')->nullable();
            $table->date('plan_send_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_header');
    }
}
