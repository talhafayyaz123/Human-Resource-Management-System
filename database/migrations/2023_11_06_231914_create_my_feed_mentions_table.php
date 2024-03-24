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
        Schema::create('my_feed_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('my_feed_id')->references('id')->on('my_feeds')->cascadeOnDelete();
            $table->string('user_id')->comment("mention user id");
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
        Schema::dropIfExists('my_feed_mentions');
    }
};
