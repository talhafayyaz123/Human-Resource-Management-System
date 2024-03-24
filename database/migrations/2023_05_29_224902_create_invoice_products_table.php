<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if(Schema::hasTable('product_invoice_categories')==false) 
        {
            Schema::create('product_invoice_categories', function (Blueprint $table) {
                $table->id();
                $table->integer('invoice_id');
                $table->string('product_ids');
                $table->integer('category_id');
                $table->decimal('quantity', 16,2)->nullable();
                $table->decimal('hourly_rate', 16,2)->nullable();
                $table->decimal('discount', 16,2)->nullable();
                $table->decimal('tax', 16, 2)->nullable();
                $table->decimal('tax_rate', 16,2)->nullable();
                $table->decimal('netto_total', 16, 2)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_products');
    }
};
