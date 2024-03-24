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
        Schema::table('sla_level_changes', function (Blueprint $table) {
            if (!Schema::hasColumn('sla_level_changes', 'factor')) {
                $table->string('factor')->nullable()->after('change');
            }
        });
        Schema::table('sla_level_incidents', function (Blueprint $table) {
            if (!Schema::hasColumn('sla_level_incidents', 'factor')) {
                $table->string('factor')->nullable()->after('incident');
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
        Schema::table('sla_level_changes', function (Blueprint $table) {
            if (Schema::hasColumn('sla_level_changes', 'factor')) {
                $table->dropColumn('factor');
            }
        });
        Schema::table('sla_level_incidents', function (Blueprint $table) {
            if (Schema::hasColumn('sla_level_incidents', 'factor')) {
                $table->dropColumn('factor');
            }
        });
    }
};
