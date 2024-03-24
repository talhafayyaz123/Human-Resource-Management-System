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
            $table->unsignedBigInteger('asset_list_id')->nullable();
            $table->foreign("asset_list_id")->references("id")->on("asset_lists")->onDelete("cascade");
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
            $table->dropColumn('asset_list_id');
        });
    }
};
