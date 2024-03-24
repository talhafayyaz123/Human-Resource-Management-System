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
        Schema::create('partner_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->foreignUuid('partner_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreignUuid('receiver_id')->nullable()->references('id')->on('companies')->nullOnDelete();
            $table->string('contact_person')->nullable();
            $table->string('created_by');
            $table->foreignId('term_id')->nullable()->references('id')->on('id')->on('terms_of_payments')->nullOnDelete();
            $table->longText('payment_terms')->nullable();
            $table->foreignId('project_id')->nullable()->references('id')->on('projects')->nullOnDelete();
            $table->date('expiry_date')->nullable();
            $table->string('status')->nullable();
            $table->string('external_order_number')->nullable();
            $table->string('identifier')->nullable();
            $table->decimal('netto_total')->nullable();
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
        Schema::dropIfExists('partner_orders');
    }
};
