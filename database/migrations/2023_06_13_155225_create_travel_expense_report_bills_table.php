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
        Schema::create('travel_expense_report_bills', function (Blueprint $table) {
            $table->id();

            $table->string('invoice_type');
            $table->string('location');
            $table->date('from_date');
            $table->date('to_date');
            $table->text('description');

            $table->unsignedBigInteger('travel_expense_id')->nullable()->references('id')->on('travel_expenses')->onDelete('cascade');
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
        Schema::dropIfExists('travel_expense_report_bills');
    }
};
