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
        Schema::create('asset_lists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('asset_number');
            $table->unsignedBigInteger('employee_id');

            //foreign key constraints
            $table->foreign('employee_id')->references('id')->on('user_profile_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_lists');
    }
};
