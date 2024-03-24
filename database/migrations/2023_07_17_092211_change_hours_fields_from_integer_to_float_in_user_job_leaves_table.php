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
        Schema::table('user_job_leaves', function (Blueprint $table) {
            $table->decimal("total_annual_leaves")->change();
            $table->decimal("additional_leaves")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_job_leaves', function (Blueprint $table) {
            $table->integer("total_annual_leaves")->change();
            $table->integer("additional_leaves")->change();
        });
    }
};
