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
        Schema::create('fleet_driver_licence_checks', function (Blueprint $table) {
            $table->id();
            $table->enum('check_type', ['online', 'personal']);
            $table->timestamp('licence_valid_until');
            $table->string('manager_id');
            //foreign key relation
            $table->unsignedBigInteger('fleet_driver_id')->nullable();
            $table->foreign('fleet_driver_id')->references('id')->on('fleet_drivers')->onDelete('cascade');
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
        Schema::dropIfExists('fleet_driver_licence_checks');
    }
};
