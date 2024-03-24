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
        Schema::table('skill_matrix_skills', function (Blueprint $table) {
            if (Schema::hasColumn('skill_matrix_skills', 'skill_group_id')){
                // Remove foreign key constraint
                $table->dropForeign(['skill_group_id']);
                // Remove the country_id column
                $table->dropColumn('skill_group_id');
            }
        });
        Schema::table('skill_matrix_team_members', function (Blueprint $table) {
            if(Schema::hasColumn('skill_matrix_team_members', 'user_team_id')){
                // Remove foreign key constraint
                //$table->dropForeign(['user_team_id']);
                // Remove the country_id column
                // $table->dropColumn('user_team_id');
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

    }
};
