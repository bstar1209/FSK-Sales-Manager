<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaibikiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daibiki', function (Blueprint $table) {
            $table->integer('id', true);
            $table->float('min', 10, 0)->nullable();
            $table->float('max', 10, 0)->nullable();
            $table->float('fee', 10, 0)->default(0);
            $table->text('information')->nullable();
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
        Schema::dropIfExists('daibiki');
    }
}
