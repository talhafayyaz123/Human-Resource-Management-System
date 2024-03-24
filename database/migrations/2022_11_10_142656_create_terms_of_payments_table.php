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
        Schema::create('terms_of_payments', function (Blueprint $table) {
            $table->id();

            $table->integer('percentage_1')->nullable();
            $table->integer('no_of_days_1')->nullable();
            $table->integer('percentage_2')->nullable();
            $table->integer('no_of_days_2')->nullable();
            $table->integer('percentage_3')->nullable();
            $table->integer('no_of_days_3')->nullable();
            $table->string('name')->nullable();
            $table->string('offer_text')->nullable();
            $table->string('order_text')->nullable();
            $table->string('invoice_text')->nullable();
            $table->string('payment_terms')->nullable();

            //Soft deletes
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
        Schema::dropIfExists('terms_of_payments');
    }
};
