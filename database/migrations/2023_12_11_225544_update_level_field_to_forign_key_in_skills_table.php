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
        Schema::table('skills', function (Blueprint $table) {
            if (Schema::hasColumn('skills', 'level')){
                $table->renameColumn('level', 'skill_level_id');
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
        Schema::table('skills', function (Blueprint $table) {
            if (Schema::hasColumn('skills', 'skill_level_id')){
                $table->renameColumn('skill_level_id', 'level');
            }
        });
    }
};
