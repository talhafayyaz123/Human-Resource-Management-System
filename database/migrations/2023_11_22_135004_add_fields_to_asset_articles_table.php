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
            $table->string('delivery_note_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->boolean('delivery_checked')->default(false)->nullable();
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
            $table->dropColumn('delivery_note_number');
            $table->dropColumn('invoice_number');
            $table->dropColumn('delivery_checked');
        });
    }
};
