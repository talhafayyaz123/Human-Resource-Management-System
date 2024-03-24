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
        Schema::table('compensation_bonuses', function (Blueprint $table) {
            $table->string('target_value')->nullable()->change();
            $table->decimal('month')->nullable();
            $table->decimal('half_month')->nullable();
            $table->decimal('year')->nullable();
            $table->decimal('bonus_faktor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compensation_bonuses', function (Blueprint $table) {
            //
        });
    }
};
