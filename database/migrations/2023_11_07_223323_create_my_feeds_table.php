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
        if (!Schema::hasTable('my_feeds')) {
            Schema::create('my_feeds', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->boolean('is_vote')->default(false);
                $table->string('user_id');
                $table->integer('moduleable_id')->nullable();
                $table->string('moduleable_type')->nullable();
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
        Schema::dropIfExists('my_feeds');
    }
};
