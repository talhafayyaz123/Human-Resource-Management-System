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
        Schema::create('performance_records', function (Blueprint $table) {
            $table->id();
            $table->string('performance_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_hours');
            $table->integer('good_will_hours');
            $table->string('advisor_id');
            //foreign key constraints
            $table->string('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->unsignedBigInteger('invoice_id')->nullable()->references('id')->on('invoices')->onDelete('set null');
            $table->unsignedBigInteger('project_id')->nullable()->references('id')->on('projects')->onDelete('set null');
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
        Schema::dropIfExists('performance_records');
    }
};
