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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->string("id")->primary();

            $table->string('supplier_number');
            $table->string('supplier_name');
            $table->string('vat_id');
            $table->string('url');
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();

            $table->unsignedBigInteger('terms_of_payment')->nullable()->references('id')->on('terms_of_payment')->onDelete('set null')->nullable();

            //Soft deletes addition
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};
