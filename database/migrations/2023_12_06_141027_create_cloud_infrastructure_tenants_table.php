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
        Schema::create('cloud_infrastructure_tenants', function (Blueprint $table) {
            $table->id();
            $table->string("system_id");
            $table->foreign('system_id')->references('id')->on('cloud_infrastructures')->onDelete('cascade');
            $table->foreignUuid('partner_id')->nullable()->references('id')->on('companies')->nullOnDelete();
            $table->string('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('companies')->nullOnDelete();
            $table->string('tenant_name')->nullable();

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
        Schema::dropIfExists('cloud_infrastructure_tenants');
    }
};
