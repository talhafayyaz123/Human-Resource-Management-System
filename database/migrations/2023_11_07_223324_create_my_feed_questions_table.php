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
        if (!Schema::hasTable('my_feed_vote_questions')) {
            Schema::create('my_feed_vote_questions', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->foreignId('my_feed_id')->references('id')->on('my_feeds')->cascadeOnDelete();
                $table->longText('text');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('my_feed_vote_questions');
    }
};
