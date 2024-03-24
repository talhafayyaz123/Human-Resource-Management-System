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
        Schema::table('my_feeds', function (Blueprint $table) {
            $table->renameColumn('poll_total_votes', 'poll_finished');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('my_feeds', function (Blueprint $table) {
            $table->renameColumn('poll_finished', 'poll_total_votes');
        });
    }
};
