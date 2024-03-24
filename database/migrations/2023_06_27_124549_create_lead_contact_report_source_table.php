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
        Schema::create('lead_contact_report_source', function (Blueprint $table) {
            $table->id();
            // Foreign key for contact_reports table
            $table->unsignedBigInteger('contact_report_source_id');
            $table->foreign('contact_report_source_id')->references('id')->on('contact_report_sources')->onDelete('cascade');
            // Foreign key for leads table
            $table->string('lead_id');
            $table->foreign('lead_id')->references('id')->on('companies')->onDelete('cascade');
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
        Schema::dropIfExists('lead_contact_report_source');
    }
};
