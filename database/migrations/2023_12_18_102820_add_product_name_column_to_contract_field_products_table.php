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
        Schema::table('contract_field_products', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_field_products', 'product_name')) {
                $table->string('product_name')->nullable();
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
        Schema::table('contract_field_products', function (Blueprint $table) {
            if (Schema::hasColumn('contract_field_products', 'product_name')) {
                $table->dropColumn('product_name');
            }
        });
    }
};
