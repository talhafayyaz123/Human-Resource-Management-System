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
        Schema::table('user_departments', function (Blueprint $table) {
            if (Schema::hasColumn("user_departments", "parent_department_id")) {
                $table->dropForeign(["parent_department_id"]);
                $table->dropColumn("parent_department_id");
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
        Schema::table('user_departments', function (Blueprint $table) {
            if (!Schema::hasColumn("user_departments", "parent_department_id")) {
                $table->string("parent_department_id")->nullable();
                $table->foreign("parent_department_id")->references("id")->on("user_departments")->onDelete("set null")->nullable();
            }
        });
    }
};
