<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('travel_expense_report_bills', function (Blueprint $table) {
            $table->longText('attachment')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travel_expense_report_bills', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }
};
