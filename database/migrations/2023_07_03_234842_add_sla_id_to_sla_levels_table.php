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
        Schema::table('sla_levels', function (Blueprint $table) {
            $table->unsignedBigInteger('sla_id')->after('id');
            $table->foreign('sla_id')->references('id')->on('service_level_agreements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sla_levels', function (Blueprint $table) {
            $table->dropColumn('sla_id');
        });
    }
};
