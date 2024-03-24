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
        Schema::table('job_levels', function (Blueprint $table) {
            // Add new integer columns
            $table->integer('experience_start')->nullable();
            $table->integer('experience_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_levels', function (Blueprint $table) {
            $table->dropColumn('experience_start');
            $table->dropColumn('experience_end');
        });
    }
};
