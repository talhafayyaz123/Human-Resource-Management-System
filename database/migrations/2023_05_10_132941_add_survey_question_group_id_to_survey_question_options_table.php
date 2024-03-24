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
        Schema::table('survey_question_options', function (Blueprint $table) {
            if (Schema::hasColumn('survey_question_options', 'survey_question_configurator_id'))
            {
                $table->dropForeign(['survey_question_configurator_id']);
                $table->dropColumn(['survey_question_configurator_id']);
            }

            if (Schema::hasColumn('survey_question_options', 'survey_question_group_id')==false)
            {
                $table->unsignedBigInteger('survey_question_group_id');
                $table->foreign('survey_question_group_id')->references('id')->on('survey_question_groups')->onDelete('cascade');
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
        Schema::table('survey_question_options', function (Blueprint $table) {
            //
        });
    }
};
