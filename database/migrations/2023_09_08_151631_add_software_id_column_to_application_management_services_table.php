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
        Schema::table('application_management_services', function (Blueprint $table) {
            $table->unsignedBigInteger('software_id')->nullable()->after('id');
            $table->foreign('software_id')->references('id')->on('product_software')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_management_services', function (Blueprint $table) {
            $table->dropForeign(['software_id']);
            $table->dropColumn('software_id');
        });
    }
};
