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
        Schema::create('sla_level_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sla_level_id');
            $table->string('priority')->nullable();
            $table->string('incident')->nullable();
            $table->string('change')->nullable();
            $table->foreign('sla_level_id')->references('id')->on('sla_levels')->onDelete('cascade');
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
        Schema::dropIfExists('sla_level_entries');
    }
};
