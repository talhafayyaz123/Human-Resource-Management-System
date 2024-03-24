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
        Schema::table('performance_records', function (Blueprint $table) {
            if (!Schema::hasColumn('performance_records', 'is_approved_by_customer')) {
                $table->boolean('is_approved_by_customer')->default(0);
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
        Schema::table('performance_records', function (Blueprint $table) {
            if (Schema::hasColumn('performance_records', 'is_approved_by_customer')) {
                $table->dropColumn('is_approved_by_customer');
            }
        });
    }
};
