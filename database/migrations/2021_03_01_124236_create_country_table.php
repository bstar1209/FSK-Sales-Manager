<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country', function (Blueprint $table) {
            $table->id();
            $table->string('cc_fips', 2);
            $table->string('cc_iso', 2)->nullable()->index('idx_cc_iso');
            $table->string('tld', 3)->nullable();
            $table->string('country_name', 100)->nullable();
            $table->enum('region', ['Malaysia', 'Japanes', 'England', 'EU', 'Franch'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country');
    }
}
