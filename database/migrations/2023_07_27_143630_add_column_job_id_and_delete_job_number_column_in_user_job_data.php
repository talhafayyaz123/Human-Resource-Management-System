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
        Schema::table('user_job_data', function (Blueprint $table) {
            if (!Schema::hasColumn('user_job_data', 'job_id')) {
                $table->foreignId('job_id')->nullable()->after('job_title')->constrained('jobs')->cascadeOnDelete()->cascadeOnUpdate();
            }
            if (Schema::hasColumn('user_job_data', 'job_number')) {
                $table->dropColumn('job_number');
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
        Schema::table('user_job_data', function (Blueprint $table) {
            $table->dropForeign(["job_id"]);
            $table->dropColumn(["job_id"]);
        });
    }
};
