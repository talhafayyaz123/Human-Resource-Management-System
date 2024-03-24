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
        Schema::table('product_stores', function (Blueprint $table) {
            $table->longText('short_description')->change()->nullable();
            $table->longText('long_description')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stores', function (Blueprint $table) {
            $table->string('short_description')->change()->nullable();
            $table->string('long_description')->change()->nullable();
        });
    }
};
