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
        Schema::create('time_tracker_companies', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->nullableMorphs('module');
            $table->text('description');
            $table->boolean('is_goodwill');

            $table->enum('status', ['new', 'pr', 'billed'])->default('new');
            $table->string('time');

            $table->string('user_id')->nullable();
            $table->string('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->unsignedBigInteger('government_timetracker_id')->nullable()->references('id')->on('time_tracker_governments');
            $table->unsignedBigInteger('performance_record_id')->nullable()->references('id')->on('performance_records');
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
        Schema::dropIfExists('time_tracker_companies');
    }
};
