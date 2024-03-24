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
        Schema::table('travel_expense_request_types', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_expense_request_types', 'customer_specific'))
                $table->boolean('customer_specific')->default(0)->after('name');

            if(!Schema::hasColumn('travel_expense_request_types', 'project_specific'))
                $table->boolean('project_specific')->default(0)->after('customer_specific');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travel_expense_request_types', function (Blueprint $table) {
            if (Schema::hasColumn('travel_expense_request_types', 'customer_specific'))
                $table->dropColumn('customer_specific');

            if (Schema::hasColumn('travel_expense_request_types', 'project_specific'))
                $table->dropColumn('project_specific');
        });
    }
};
