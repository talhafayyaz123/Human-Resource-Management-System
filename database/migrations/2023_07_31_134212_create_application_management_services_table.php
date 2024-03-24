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
        if (!Schema::hasTable('application_management_services')) {
            Schema::create('application_management_services', function (Blueprint $table) {
                $table->id();
                $table->string('ams_number');
                $table->string('customer_id');
                $table->foreign('customer_id')->references('id')->on('companies')->onDelete('cascade');
                $table->decimal('service_hours_year');
                $table->decimal('used_hours');
                $table->decimal('hourly_rate');
                $table->decimal('monthly_amount');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_management_services');
    }
};
