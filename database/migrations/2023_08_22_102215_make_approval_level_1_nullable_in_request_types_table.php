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
            // Make the 'approval_level_1' column nullable
            $table->string('approval_level_1')->nullable()->change();
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
            // If you ever need to revert the migration, you can remove the 'nullable' modifier
            $table->string('approval_level_1')->change();
        });
    }
};
