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
        Schema::create('contact_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->nullable();
            $table->string('subject')->nullable();
            $table->text('text')->nullable();
            $table->timestamp('resubmit_on')->nullable();

            $table->enum('type', ["customer", "lead"])->nullable(); //Either existing company or new lead (linked to company module)

            $table->string('priority')->nullable();
            $table->string('contact_type')->nullable(); //mail, phone, facebook, etc.
            $table->enum('initiated_by', ["customer", "docshero"])->nullable();

            //Add foreign key relationship
            $table->string('created_by_employee')->nullable();
            $table->string('company_id')->nullable()->references('id')->on('companies')->onDelete('set null');
            $table->unsignedBigInteger('category_id')->references('id')->on('report_categories')->onDelete('set null');

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
        Schema::dropIfExists('contact_reports');
    }
};
