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
            $table->unsignedBigInteger('asset_delivery_id')->nullable();
            $table->foreign("asset_delivery_id")->references("id")->on("asset_deliveries")->onDelete("cascade");
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
            $table->dropForeign(['asset_delivery_id']);
            $table->dropColumn(['asset_delivery_id']);
        });
    }
};
