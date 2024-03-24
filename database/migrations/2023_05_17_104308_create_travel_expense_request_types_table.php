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
        Schema::create('travel_expense_request_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('approval_level_1');
            $table->string('approval_level_2')->nullable();
            $table->string('approval_level_3')->nullable();
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
        Schema::dropIfExists('travel_expense_request_types');
    }
};
