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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('task_id');
            $table->string('priority');
            $table->enum('status', ['new', 'done', 'in-work', 'in-review', 'blocked'])->default("new");

            $table->string('estimated_time')->nullable();
            $table->string('spent_time')->nullable();

            $table->string('name');
            $table->text('description');
            $table->date('planned_start_date')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('earliest_start_date')->nullable();
            $table->date('actual_finished_date')->nullable();
            $table->date('expected_finished_date')->nullable();
            $table->date('planned_finished_date')->nullable();
            $table->json('task_hours')->nullable();
            $table->json('relationships')->nullable();

            $table->string('comments')->nullable();

            $table->string('employee_id')->nullable(); //Docs hero user

            //Foreign Keys
            $table->unsignedBigInteger('milestone_id')->references('id')->on('milestones')->onDelete('cascade');

            //Soft deletes
            $table->softDeletes();

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
        Schema::dropIfExists('tasks');
    }
};
