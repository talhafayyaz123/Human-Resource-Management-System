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
        Schema::table('time_tracker_governments', function (Blueprint $table) {
            // Add the nullable company_id column
            $table->string('company_id')->nullable();

            // Define the foreign key constraint
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_tracker_governments', function (Blueprint $table) {
            // Drop the foreign key constraint and company_id column if needed
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
