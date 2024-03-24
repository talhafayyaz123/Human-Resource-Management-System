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
        Schema::table('travel_expense_approvers', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_expense_approvers', 'level')) {
                $table->integer('level')->nullable();
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
        Schema::table('travel_expense_approvers', function (Blueprint $table) {
            if (Schema::hasColumn('travel_expense_approvers', 'level')) {
                $table->dropColumn('level');
            }
        });
    }
};
