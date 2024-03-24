<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('sla_infrastructures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sla_id');
            $table->string('name');
            $table->integer('is_access')->default(0)->comment('1=Yes, 0=No');
            $table->integer('user_included')->default(0);
            $table->string('additional_user_cost')->nullable();
            $table->foreign('sla_id')->references('id')->on('service_level_agreements')->onDelete('cascade');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('sla_infrastructures');
    }
};
