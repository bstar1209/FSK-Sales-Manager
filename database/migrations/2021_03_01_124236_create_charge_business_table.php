<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargebusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charge_business', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('staff_id');
            $table->string('username_eng', 100);
            $table->string('username_jap', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('address', 200);
            $table->string('tel', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('mail', 50);
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
        Schema::dropIfExists('charge_business');
    }
}
