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
        Schema::table('employee_interviews', function (Blueprint $table) {
            $table->renameColumn('employee_name', 'employee_id');
            $table->renameColumn('goal_acheivement', 'goal_achievement');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_interviews', function (Blueprint $table) {
            //
        });
    }
};
