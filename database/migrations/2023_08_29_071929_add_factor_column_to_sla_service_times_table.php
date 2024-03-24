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
        Schema::table('sla_service_times', function (Blueprint $table) {
            if (!Schema::hasColumn('sla_service_times', 'factor')) {
                $table->decimal('factor')->default(0)->after('to');
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
        Schema::table('sla_service_times', function (Blueprint $table) {
            if (Schema::hasColumn('sla_service_times', 'factor')) {
                $table->dropColumn('factor');
            }
        });
    }
};
