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
        Schema::table('elo_business_solutions', function (Blueprint $table) {
            if (!Schema::hasColumn('elo_business_solutions', 'type')){
                $table->enum('type', ['elo-cloud-business-solution', 'elo-cloud-module', 'elo-cloud-database', 'elo-cloud-repository'])->default(null)->after('install_name');
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
        Schema::table('elo_business_solutions', function (Blueprint $table) {
            if (Schema::hasColumn('elo_business_solutions', 'type')){
                $table->dropColumn('type');
            }
        });
    }
};
