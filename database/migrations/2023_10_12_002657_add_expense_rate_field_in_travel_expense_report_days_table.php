<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('travel_expense_report_days', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_expense_report_days', 'expense_rate')) {
                $table->decimal('expense_rate')->after('to_time')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travel_expense_report_days', function (Blueprint $table) {
            if (Schema::hasColumn('travel_expense_report_days', 'expense_rate')) {
                $table->dropColumn('expense_rate');
            }
        });
    }
};
