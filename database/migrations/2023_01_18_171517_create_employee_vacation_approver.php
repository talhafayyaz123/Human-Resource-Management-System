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
        Schema::create('employee_vacation_approver', function (Blueprint $table) {
            $table->id();
            $table->string('employee_vacation_id')->references('id')->on('employee_vacations')->onDelete('cascade');
            $table->string('approver_id'); //User Id
            $table->string('remarks')->nullable();
            $table->string('status')->default("pending");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_vacation_approver');
    }
};
