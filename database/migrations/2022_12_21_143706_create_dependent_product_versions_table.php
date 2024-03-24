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
        Schema::create('dependent_product_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('version_id')->references('id')->on('product_versions');
            $table->unsignedBigInteger('dependent_version_id')->references('id')->on('product_versions');
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
        Schema::dropIfExists('dependent_product_versions');
    }
};
