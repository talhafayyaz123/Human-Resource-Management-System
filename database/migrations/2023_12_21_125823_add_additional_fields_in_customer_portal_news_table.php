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
        Schema::table('customer_portal_news', function (Blueprint $table) {
            $table->enum('language', ['english', 'german'])->default('english')->after('description');

            // Add 'german_description' column after 'language'
            $table->longText('german_description')->nullable()->after('language');

            //change the normal description to nullable since it can be nullable
            $table->longText('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_portal_news', function (Blueprint $table) {
            $table->dropColumn('language');
            $table->dropColumn('german_description');

            // Revert the 'description' column to its previous state
            $table->longText('description')->nullable(false)->change();
        });
    }
};
