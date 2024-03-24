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
        Schema::table('user_profile_data', function (Blueprint $table) {
            $table->string("user_department_id")->nullable();
            $table->foreign("user_department_id")->references("id")->on("user_departments")->onDelete("set null")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profile_data', function (Blueprint $table) {
            $table->dropForeign("user_department_id");
            $table->dropColumn("user_department_id");
        });
    }
};
