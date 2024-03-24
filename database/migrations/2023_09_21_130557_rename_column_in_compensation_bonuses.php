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
            $table->renameColumn('half_month', 'half_year');
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
            $table->renameColumn('half_year', 'half_month');
        });
    }
};
