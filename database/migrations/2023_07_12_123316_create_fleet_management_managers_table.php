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
        Schema::create('fleet_management_managers', function (Blueprint $table) {
            $table->id();
            $table->string('manager_id');
            $table->unsignedBigInteger('interval_setting_id');
            $table->foreign('interval_setting_id')->nullable()->references('id')->on('fleet_management_interval_settings')->onDelete('cascade');
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
        Schema::dropIfExists('fleet_management_managers');
    }
};
