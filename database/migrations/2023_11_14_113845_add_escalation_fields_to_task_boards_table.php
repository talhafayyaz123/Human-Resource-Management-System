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
            $table->boolean('is_escalation_mode')->default(false)->nullable();
            $table->decimal('escalation_hours')->nullable();
            $table->string('escalation_manager')->nullable();
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
            $table->dropColumn('is_escalation_mode');
            $table->dropColumn('escalation_hours');
            $table->dropColumn('escalation_manager');
        });
    }
};
