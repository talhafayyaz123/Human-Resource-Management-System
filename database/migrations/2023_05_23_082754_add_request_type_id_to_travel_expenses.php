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
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('request_type_id')->nullable()->references('id')->on('travel_expense_request_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->dropColumn('request_type_id');
        });
    }
};
