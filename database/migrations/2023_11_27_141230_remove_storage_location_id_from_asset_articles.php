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
        Schema::table('asset_articles', function (Blueprint $table) {
            if (Schema::hasColumn('asset_articles', 'storage_location_id')) {
                $table->dropForeign(['storage_location_id']);
                $table->dropColumn(['storage_location_id']);
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
        Schema::table('asset_articles', function (Blueprint $table) {
            $table->unsignedBigInteger('storage_location_id')->nullable();
            $table->foreign('storage_location_id')->references('id')->on('storage_locations')->onDelete('cascade');
        });
    }
};
