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
        Schema::table('product_store_requests', function (Blueprint $table) {
            //
            $table->foreignUuid('partner_id')->nullable()->references('id')->on('companies')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->nullOnDelete();
            $table->integer('service_hours')->nullable();
            $table->longText('service_description')->nullable();
            $table->longText('delimitation')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_store_requests', function (Blueprint $table) {
            //
            $table->dropForeign(['partner_id']);
            $table->dropColumn(['partner_id']);
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            $table->dropColumn('service_hours');
            $table->dropColumn('service_description');
            $table->dropColumn('delimitation');
        });
    }
};