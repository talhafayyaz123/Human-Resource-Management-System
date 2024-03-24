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
        Schema::table('employee_interviews', function (Blueprint $table) {
            //employee tab values
            $table->json('satisfaction_with_superiors')->nullable();
            $table->json('overall_satisfaction')->nullable();
            $table->longText('training_personal_development')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_interviews', function (Blueprint $table) {
            //employee tab values
            $table->dropColumn('satisfaction_with_superiors');
            $table->dropColumn('overall_satisfaction');
            $table->dropColumn('training_personal_development');
        });
    }
};
