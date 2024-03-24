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
        Schema::create('compensation_bonuses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('target_type', ['consulting_individual_value', 'consulting_team_value', 'sale_individual_value', 'sale_team_value']);
            $table->string('target_value');
            $table->enum('level', ['one', 'two', 'three']);

            $table->unsignedBigInteger('compensation_id');
            $table->foreign('compensation_id')->references('id')->on('user_compensation_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compensation_bonuses');
    }
};
