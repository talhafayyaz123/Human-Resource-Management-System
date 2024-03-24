<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditor_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->string('payment_terms')->nullable();
            $table->enum('invoice_type', ['invoice', 'invoice-correction', 'invoice-storno'])->default('invoice');
            $table->enum('status', ['draft', 'approved', 'sent', 'paid', 'warning level 1', 'warning level 2', 'warning level 3'])->default('draft');
            $table->dateTime('due_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('start_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('end_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->foreignUuid('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->string('user_id');
            $table->foreignId('invoice_period')->references('id')->on('payment_periods')->cascadeOnDelete();
            $table->decimal('total_amount');
            $table->decimal('total_amount_without_tax');
            $table->decimal('total_tax_amount');
            $table->foreignId('terms_of_payment')->references('id')->on('terms_of_payments')->cascadeOnDelete();
            $table->text('custom_notes_field')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->dateTime('draft_status_change_date')->nullable();
            $table->string('contact_person')->nullable();
            $table->enum('receiver_type', ['customer', 'partner'])->default('customer');
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
        Schema::dropIfExists('creditor_invoices');
    }
};
