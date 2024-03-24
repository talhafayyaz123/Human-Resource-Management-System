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
        if (Schema::hasColumn('performance_record_entries', 'task_number')==false)
        {
            Schema::table('performance_record_entries', function (Blueprint $table) {
                $table->string('task_number')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('performance_record_entries', function (Blueprint $table) {
            //
        });
    }
};
