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
        Schema::create('cloud_infrastructure_tenant_repositories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("cloud_tenant_id");
            $table->foreign('cloud_tenant_id')->references('id')->on('cloud_infrastructure_tenants')->onDelete('cascade');
            $table->integer('database_size')->default(0);
            $table->integer('data_size')->default(0);
            $table->string('name')->nullable();
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
        Schema::dropIfExists('cloud_infrastructure_tenant_repositories');
    }
};
