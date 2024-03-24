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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('project_id');

            $table->date('planned_start_date')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('earliest_start_date')->nullable();
            $table->date('actual_finished_date')->nullable();
            $table->date('expected_finished_date')->nullable();
            $table->date('planned_finished_date')->nullable();

            $table->string('name');
            $table->text('description');

            $table->string('estimated_time')->nullable();
            $table->string('needed_time')->nullable();
            $table->enum('status', ['new', 'done', 'in-work', 'in-review', 'blocked'])->default("new");

            $table->string('comments')->nullable();

            //Foreign Keys
            $table->string('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->string('user_id')->nullable();

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
        Schema::dropIfExists('projects');
    }
};
