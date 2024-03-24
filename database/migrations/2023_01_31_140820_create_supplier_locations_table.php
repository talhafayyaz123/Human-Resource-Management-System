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
        Schema::create('supplier_locations', function (Blueprint $table) {
            $table->string("id")->primary();

            $table->string('address_first');
            $table->string('address_second')->nullable();
            $table->string('city');
            $table->string('country')->nullable();
            $table->string('state');
            $table->string('zip');
            $table->boolean('is_head_quarters');

            $table->string('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

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
        Schema::dropIfExists('supplier_locations');
    }
};
