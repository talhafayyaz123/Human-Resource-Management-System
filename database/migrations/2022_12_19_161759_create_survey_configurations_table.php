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
        Schema::create('survey_configurations', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_expert_mode')->default(false);
            $table->string('title')->nullable();
            $table->string('route')->nullable();
            $table->string('function_name')->nullable();
            $table->string('code')->nullable();
            $table->string('success_feedback')->nullable();
            $table->json('arguments')->nullable();

            //foreign key constraints
            $table->string('survey_id');
            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');

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
        Schema::dropIfExists('survey_configurations');
    }
};
