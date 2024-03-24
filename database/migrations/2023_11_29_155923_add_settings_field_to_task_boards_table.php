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
        Schema::table('task_boards', function (Blueprint $table) {
            $table->string('standard_icon')->nullable();
            $table->string('standard_color')->nullable();
            $table->string('standard_priority')->nullable();
            $table->string('standard_time_tracker')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_boards', function (Blueprint $table) {
            $table->dropColumn('standard_icon');
            $table->dropColumn('standard_color');
            $table->dropColumn('standard_priority');
            $table->dropColumn('standard_time_tracker');
        });
    }
};
