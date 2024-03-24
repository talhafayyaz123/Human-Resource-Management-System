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
        Schema::create('companies', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string('company_number');
            $table->string('company_name');
            $table->string('vat_id');
            $table->string('url')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('invoice_email')->nullable();
            $table->string('default_service_hourly_rate')->nullable();
            $table->string('default_service_daily_rate')->nullable();
            $table->enum('type', ['premise', 'private', 'public']);
            $table->enum('customer_type', ["customer", "lead"])->nullable(); //Either existing company or new lead

            $table->unsignedBigInteger('default_service_product')->nullable()->references('id')->on('products')->onDelete('set null')->nullable();
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
        Schema::dropIfExists('companies');
    }
};
