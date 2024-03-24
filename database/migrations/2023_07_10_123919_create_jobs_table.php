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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('j_number');
            $table->string('j_title');
            $table->foreignId('job_level_id')->constrained('job_levels')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('form_of_contract_id')->constrained('form_of_contract')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('core_functions')->nullable(true);
            $table->text('qualifications')->nullable(true);
            $table->text('j_description')->nullable(true);
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
        Schema::dropIfExists('jobs');
    }
};
