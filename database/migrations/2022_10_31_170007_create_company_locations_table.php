<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_locations', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string('address_first');
            $table->string('address_second')->nullable();
            $table->string('city');
            $table->string('country');
            $table->string('state');
            $table->string('zip');
            $table->boolean('is_head_quarters');

            $table->string('company_id')->references('id')->on('companies')->onDelete('cascade');

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
        Schema::dropIfExists('company_locations');
    }
};
