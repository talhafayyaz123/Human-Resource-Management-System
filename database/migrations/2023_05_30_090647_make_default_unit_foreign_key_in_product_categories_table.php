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
        Schema::table('product_categories', function (Blueprint $table) {
            $table->unsignedBigInteger("default_unit")->nullable()->change();
            $table->foreign("default_unit")->references("id")->on("product_units")->onDelete("SET NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(["default_unit"]);
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->string("default_unit")->nullable()->change();
        });
    }
};
