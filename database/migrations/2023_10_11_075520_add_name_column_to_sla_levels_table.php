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
        Schema::table('sla_levels', function (Blueprint $table) {
            if (!Schema::hasColumn('sla_levels', 'name')) {
                $table->string('name')->nullable();
            }
            if (Schema::hasColumn('sla_levels', 'name_urgent')) {
                $table->dropColumn('name_urgent');
            }
            if (Schema::hasColumn('sla_levels', 'name_high')) {
                $table->dropColumn('name_high');
            }
            if (Schema::hasColumn('sla_levels', 'name_low')) {
                $table->dropColumn('name_low');
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
        Schema::table('sla_levels', function (Blueprint $table) {
            if (Schema::hasColumn('sla_levels', 'name')) {
                $table->dropColumn('name');
            }
            if (!Schema::hasColumn('sla_levels', 'name_urgent')) {
                $table->string('name_urgent')->nullable();
            }
            if (!Schema::hasColumn('sla_levels', 'name_high')) {
                $table->string('name_high')->nullable();
            }
            if (!Schema::hasColumn('sla_levels', 'name_low')) {
                $table->string('name_low')->nullable();
            }
        });
    }
};
