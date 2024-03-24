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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'infrastructure_setting_id')){
                $table->unsignedBigInteger('infrastructure_setting_id')->nullable();
                $table->foreign('infrastructure_setting_id')->references('id')->on('elo_business_solutions')->onDelete('SET NULL');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'infrastructure_setting_id')){
                $table->dropForeign('infrastructure_setting_id');
                $table->dropColumn('infrastructure_setting_id');
            }
        });
    }
};
