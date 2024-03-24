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
        Schema::create('product_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('time_needed')->nullable();
            $table->longText('details')->nullable();
            $table->unsignedBigInteger('product_version_id');

            $table->foreign('product_version_id')->references('id')->on('product_versions')->onDelete('cascade');
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
        Schema::dropIfExists('product_configurations');
    }
};
