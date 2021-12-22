<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplateInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_info', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('template_name', 100);
            $table->text('template_content')->nullable();
            $table->text('template_params')->nullable();
            $table->integer('template_index');
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
        Schema::dropIfExists('template_info');
    }
}
