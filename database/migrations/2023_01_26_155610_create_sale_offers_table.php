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
        Schema::create('sale_offers', function (Blueprint $table) {
            $table->id();

            $table->string('sale_offer_number')->nullable();
            $table->longText("offer_comments")->nullable();
            $table->enum("receiver_type", ["customer", "lead"]);
            $table->enum("offer_type", ["budget", "offer"]);
            $table->enum("template", ["empty", "project"])->nullable();
            $table->enum("grouped_by", ["nothing", "category"])->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('created_by');
            $table->timestamp('expiry_date');
            $table->string('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('term_id')->references('id')->on('terms_of_payment')->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->nullable()->references('id')->on('projects')->onDelete('cascade')->nullable();
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
        Schema::dropIfExists('sale_offers');
    }
};
