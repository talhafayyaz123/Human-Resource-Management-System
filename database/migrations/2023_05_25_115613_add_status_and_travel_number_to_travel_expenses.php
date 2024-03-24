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
            $table->string('status', 20)->default('draft')->before('created_by');
            $table->string('travel_number')->nullable()->before('created_by');
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
            $table->dropColumn('status');
            $table->dropColumn('travel_number');
        });
    }
};
