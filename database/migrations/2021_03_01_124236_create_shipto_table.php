<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiptoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_to', function (Blueprint $table) {
            $table->id();
            $table->string('comp_name', 100)->nullable();
            $table->string('staff', 50)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('address1', 200)->nullable();
            $table->string('tel', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('zip', 45)->nullable();
            $table->string('representative', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('city', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ship_to');
    }
}
