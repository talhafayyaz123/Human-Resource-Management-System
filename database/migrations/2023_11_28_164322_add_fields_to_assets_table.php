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
        Schema::table('assets', function (Blueprint $table) {
            $table->text('asset_description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->boolean('active_asset')->default(false);
            $table->boolean('archived_asset')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('asset_description');
            $table->dropColumn('manufacturer');
            $table->dropColumn('active_asset');
            $table->dropColumn('archived_asset');
        });
    }
};
