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
        Schema::table('product_invoice', function (Blueprint $table) {
            if (!Schema::hasColumn('product_invoice', 'parent_product_invoice_id')) {
                $table->foreignId("parent_product_invoice_id")->nullable()->references("id")->on("product_invoice")->cascadeOnDelete();
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
        Schema::table('product_invoice', function (Blueprint $table) {
            if (Schema::hasColumn('product_invoice', 'parent_product_invoice_id')) {
                $table->dropConstrainedForeignId('parent_product_invoice_id');
            }
        });
    }
};
