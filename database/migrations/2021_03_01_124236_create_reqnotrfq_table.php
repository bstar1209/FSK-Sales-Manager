<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqnotrfqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_not_rfq', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('total_req')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('req_not_rfq');
    }
}
