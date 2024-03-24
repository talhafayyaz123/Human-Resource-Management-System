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
            $table->enum("marital_status", ["married", "single", "divorced", "widowed"]);
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
            $table->dropColumn("marital_status");
        });
    }
};
