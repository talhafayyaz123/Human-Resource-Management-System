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
        Schema::create('my_feed_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('my_feed_id')->references('id')->on('my_feeds')->cascadeOnDelete();
            $table->foreignId('hash_tag_id')->references('id')->on('hash_tags')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('my_feed_tags');

    }
};
