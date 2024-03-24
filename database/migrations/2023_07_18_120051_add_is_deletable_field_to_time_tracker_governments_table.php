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
        Schema::table('time_tracker_governments', function (Blueprint $table) {
            if (!Schema::hasColumn('time_tracker_governments', 'is_deletable')) {
                $table->boolean('is_deletable')->default(1);
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
        Schema::table('time_tracker_governments', function (Blueprint $table) {
            if (Schema::hasColumn('time_tracker_governments', 'is_deletable')) {
                $table->dropColumn('is_deletable');
            }
        });
    }
};
