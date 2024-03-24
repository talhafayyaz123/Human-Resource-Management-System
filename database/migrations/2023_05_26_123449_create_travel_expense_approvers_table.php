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
        Schema::create('travel_expense_approvers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_expense_id');
            $table->string('approver_id');
            $table->string('status')->default('pending');

            $table->foreign('travel_expense_id')->references('id')->on('travel_expenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel_expense_approvers');
    }
};
