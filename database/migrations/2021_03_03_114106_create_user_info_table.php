<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->id();
            $table->integer('address_id');
            $table->enum('type', ['customer', 'supplier']);
            $table->string('company_name', 100);
            $table->string('company_name_kana', 100);
            $table->integer('rank')->default(0);
            $table->integer('order_qty')->default(0);
            $table->double('order_money')->default(0);
            $table->integer('est_req_time')->nullable();
            $table->integer('est_ans_time')->nullable();
            $table->string('message1', 500)->nullable();
            $table->string('message2', 500)->nullable();
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
        Schema::dropIfExists('user_info');
    }
}
