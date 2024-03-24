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
        Schema::table('company_locations', function (Blueprint $table) {

            $table->unsignedBigInteger('country_id')->nullable()->after('company_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_locations', function (Blueprint $table) {
            // Remove foreign key constraint
            $table->dropForeign(['country_id']);

            // Remove the country_id column
            $table->dropColumn('country_id');
        });
    }
};
