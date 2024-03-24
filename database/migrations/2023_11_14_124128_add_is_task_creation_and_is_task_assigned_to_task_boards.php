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
        Schema::table('task_boards', function (Blueprint $table) {
            $table->boolean('is_task_creation_notify')->default(false)->nullable();
            $table->boolean('is_task_assigned_notify')->default(false)->nullable();
            $table->renameColumn('is_escalation_mode', 'is_escalation_mode_notify');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_boards', function (Blueprint $table) {
            $table->dropColumn('is_task_creation');
            $table->dropColumn('is_task_assigned');
        });
    }
};
