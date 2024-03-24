<?php

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\InvoiceCategory;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();

            $table->string('grouped_by')->nullable();
            $table->string('payment_terms')->nullable();

            $table->enum('invoice_type', InvoiceType::ALL)->default(InvoiceType::INVOICE);
            $table->enum('invoice_category', InvoiceCategory::ALL);
            $table->enum('status', InvoiceStatus::ALL)->default(InvoiceStatus::DRAFT);


            $table->timestamp('due_date');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            //Add foreign key relationship
            $table->string('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('user_id')->references('user_id')->on('user_profile_data')->onDelete('cascade');

            //Add foreign key relationship
            $table->unsignedBigInteger('system_id')->references('id')->on('systems')->onDelete('cascade')->nullable();
            $table->unsignedBigInteger('invoice_period')->references('id')->on('payment_periods')->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->references('id')->on('projects')->onDelete('cascade')->nullable();
            $table->unsignedBigInteger('performance_record_id')->references('id')->on('performance_records')->onDelete('set null')->nullable();

            //total amount of all the products in an invoice w.r.t tax
            $table->string('total_amount');
            $table->string('total_amount_without_tax');
            $table->string('total_tax_amount');

            //On Premise
            $table->text('terms_of_payment')->nullable();
            $table->text('custom_notes_field')->nullable();

            //soft deletes addition
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
        Schema::dropIfExists('invoices');
    }
};
