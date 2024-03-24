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
        Schema::create('product_dependencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_version_id');
            $table->unsignedBigInteger('dependent_version_id');
            $table->timestamps();
            $table->unique(['product_version_id', 'dependent_version_id'], 'unique_versions');
            $table->foreign('product_version_id')->references('id')->on('product_versions')->onDelete('cascade');
            $table->foreign('dependent_version_id')->references('id')->on('product_versions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
