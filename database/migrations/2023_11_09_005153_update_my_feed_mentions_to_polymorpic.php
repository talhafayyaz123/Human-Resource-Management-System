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
        Schema::table('my_feed_mentions', function (Blueprint $table) {
            if (Schema::hasColumn('my_feed_mentions', 'my_feed_id')){
                $table->dropForeign(['my_feed_id']);
                $table->dropColumn('my_feed_id');
            }
            $table->string('morphable_id');
            $table->string('morphable_type');
            $table->index(['morphable_id', 'morphable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('my_feed_mentions', function (Blueprint $table) {
            $table->dropColumn('morphable_id');
            $table->dropColumn('morphable_type');
        });
    }
};
