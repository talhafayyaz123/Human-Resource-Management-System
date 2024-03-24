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
        Schema::create('modify_my_data_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modify_my_data_id')->references('id')->on('modify_my_data')->cascadeOnDelete();
            $table->foreignId('manager_id')->references('id')->on('user_profile_data')->cascadeOnDelete();
            $table->boolean('changed_by')->default(0);
            $table->dateTime('changed_at')->nullable();
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
        Schema::dropIfExists('modify_my_data_managers');
    }
};
