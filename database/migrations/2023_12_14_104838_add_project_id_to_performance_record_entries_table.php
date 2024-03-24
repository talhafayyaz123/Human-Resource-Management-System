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
        Schema::table('performance_record_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable()->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('performance_record_entries', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
};
