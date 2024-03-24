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
        Schema::table('my_tasks', function (Blueprint $table) {
            $table->enum('time_tracker_type', ['company', 'government'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('my_tasks', function (Blueprint $table) {
            $table->dropColumn('time_tracker_type');
        });
    }
};
