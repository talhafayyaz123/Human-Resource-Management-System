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
        Schema::create('invoice_templates', function (Blueprint $table) {
            $table->id();
            $table->string('group_by')->default('nothing');
            $table->enum('invoice_category', ['license', 'maintenance', 'service', 'ams', 'hosting', 'cloud-software']);
            $table->enum('receiver_type', ['customer', 'partner'])->default('customer');
            $table->foreignUuid('company_id')->nullable()->references('id')->on('companies')->nullOnDelete();
            $table->string('contact_person')->nullable();
            $table->integer('system_id')->nullable();
            $table->boolean('apply_reverse_change')->default(false);
            $table->foreignId('project_id')->nullable()->references('id')->on('projects')->nullOnDelete();
            $table->foreignId('performance_record_id')->nullable()->references('id')->on('performance_records')->nullOnDelete();
            $table->string('user_id');
            $table->decimal('total_amount')->default(0);
            $table->decimal('total_amount_without_tax')->default(0);
            $table->decimal('total_tax_amount')->default(0);
            $table->longText('payment_terms')->nullable();
            $table->integer('terms_of_payment')->nullable();
            $table->longText('custom_notes_fields')->nullable();
            $table->integer('invoice_period')->nullable();
            $table->string('start_date_expression')->nullable();
            $table->string('end_date_expression')->nullable();
            $table->string('create_date_expression')->nullable();
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
        Schema::dropIfExists('invoice_templates');
    }
};
