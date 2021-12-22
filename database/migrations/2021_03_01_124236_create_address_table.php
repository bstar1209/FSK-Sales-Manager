<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('tel', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('address1', 20);
            $table->string('address2', 50)->nullable();
            $table->string('address3', 60)->nullable();
            $table->string('address4', 100)->nullable();
            $table->string('comp_type', 500)->nullable();
            $table->string('part_name', 250)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('jobsType', 150)->nullable();
            $table->string('homepages', 300)->nullable();
            $table->boolean('address_type')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
