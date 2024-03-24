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
        if (!Schema::hasTable("executive_board")) {
            Schema::create('executive_board', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger("user_id");
                $table->foreign("user_id")->references("id")->on("user_profile_data")->onDelete("cascade");
                $table->timestamps();
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
        Schema::dropIfExists('executive_board');
    }
};
