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
            if (Schema::hasColumn('company_locations', 'state')) {
                $table->string('state')->nullable()->change();
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
        Schema::table('company_locations', function (Blueprint $table) {
            if (Schema::hasColumn('company_locations', 'state')) {
                $table->string('state')->nullable(false)->change();
            }
        });
    }
};
