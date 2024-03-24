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
        Schema::create('employee_vacations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('leave_type');

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->string('special_leave_type')->nullable();

            $table->json('replacements')->nullable(); //User ids
            $table->string('user_id')->nullable();
            $table->boolean('is_special_leave')->default(0);
            $table->boolean('is_paid')->default(1);
            $table->text('special_leave_comments')->nullable();

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
        Schema::dropIfExists('employee_vacations');
    }
};
