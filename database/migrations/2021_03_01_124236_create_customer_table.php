<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('user_info_id');
            $table->string('name', 100)->nullable();
            $table->string('url', 100)->nullable();
            $table->char('password', 32);
            $table->integer('conditions')->nullable();
            $table->string('representative', 50)->default('');
            $table->string('representative_business', 50)->nullable();
            $table->string('comment_business')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('is_friend')->nullable();
            $table->integer('ord_cal_time')->nullable();
            $table->integer('search_time')->nullable();
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
        Schema::dropIfExists('customer');
    }
}
