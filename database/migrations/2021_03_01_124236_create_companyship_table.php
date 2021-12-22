<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_ship', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 100);
            $table->string('company_name_kana', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('address', 200);
            $table->string('tel', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('mail', 50)->nullable();
            $table->boolean('is_fedex')->default(false);
            $table->boolean('is_dhl')->default(false);
            $table->boolean('is_yamato')->default(false);
            $table->boolean('is_payFeeShip_delivery')->default(false);
            $table->boolean('is_payFeeShip_receive')->default(false);
            $table->boolean('is_insurance')->default(false);
            $table->string('tracking_number', 50)->nullable();
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
        Schema::dropIfExists('company_ship');
    }
}
