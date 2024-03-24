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
            if (!Schema::hasColumn('product_store_requests', 'is_show_on_admin')){
                $table->boolean('is_show_on_admin')->default(true);
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
        Schema::table('product_store_requests', function (Blueprint $table) {
            if (Schema::hasColumn('product_store_requests', 'is_show_on_admin')){
                $table->dropColumn('is_show_on_admin');
            }
        });
    }
};
