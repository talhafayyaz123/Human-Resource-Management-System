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
        Schema::create('my_feeds', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->longText('text');
            $table->boolean('is_vote')->default(false);
            $table->nullableMorphs('moduleable');
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
        Schema::dropIfExists('my_feeds');
    }
};
