<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('sla_service_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sla_infrastructure_id');
            $table->longText('days')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->foreign('sla_infrastructure_id')->references('id')->on('sla_infrastructures')->onDelete('cascade');
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
        Schema::dropIfExists('sla_service_times');
    }
};
