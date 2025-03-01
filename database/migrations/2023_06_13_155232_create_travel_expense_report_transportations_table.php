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
        Schema::create('travel_expense_report_transportations', function (Blueprint $table) {
            $table->id();

            $table->integer('driven_kilometer');
            $table->string('license_number');

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
        Schema::dropIfExists('travel_expense_report_transportations');
    }
};
