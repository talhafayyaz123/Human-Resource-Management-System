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
        Schema::create('product_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_version_id');
            $table->string('type');
            $table->string('key');
            $table->string('file_name')->nullable();
            $table->longText('value');



            //foreign key constraints
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
        Schema::dropIfExists('product_parameters');
    }
};
