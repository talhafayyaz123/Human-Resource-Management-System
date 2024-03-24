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
        Schema::table('sla_service_times', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sla_id');
            $table->dropConstrainedForeignId('sla_infrastructure_id');
            $table->string('name')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sla_service_times', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->unsignedBigInteger('sla_id')->after('id');
            $table->foreign('sla_id')->references('id')->on('service_level_agreements')->onDelete('cascade');
            $table->unsignedBigInteger('sla_infrastructure_id')->after('id');
            $table->foreign('sla_infrastructure_id')->references('id')->on('sla_infrastructures')->onDelete('cascade');
        });
    }
};
