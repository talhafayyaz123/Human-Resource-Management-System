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
        Schema::table('workshop_templates', function (Blueprint $table) {
            $table->dropForeign("workshop_templates_company_id_foreign");
            $table->dropColumn("company_id");
            $table->dropForeign("workshop_templates_system_id_foreign");
            $table->dropColumn("system_id");
            $table->renameColumn("consultant", "consultant_id");
            $table->string("template_name");
            $table->string("template_version");
            $table->enum("status", ["draft", "stable"])->default("draft");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workshop_templates', function (Blueprint $table) {
            $table->string("company_id");
            $table->unsignedBigInteger("system_id");
            $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
            $table->foreign("system_id")->references("id")->on("systems")->onDelete("cascade");
            $table->renameColumn("consultant_id", "consultant");
            $table->dropColumn("template_name");
            $table->dropColumn("template_version");
            $table->dropColumn("status");
        });
    }
};
