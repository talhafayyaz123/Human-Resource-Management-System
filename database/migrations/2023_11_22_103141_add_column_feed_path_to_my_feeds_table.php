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
            if (!Schema::hasColumn('my_feeds', 'feed_path')) {
                $table->string('feed_path')->nullable()->after('moduleable_id');
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
        Schema::table('my_feeds', function (Blueprint $table) {
            if (Schema::hasColumn('my_feeds', 'feed_path')){
                $table->dropColumn('feed_path');
            }

        });
    }
};
