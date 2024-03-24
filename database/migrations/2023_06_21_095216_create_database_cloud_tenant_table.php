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
        if (Schema::hasTable('database_cloud_tenant') == false) {
            
            Schema::create('database_cloud_tenant', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('database_cloud_id');
                $table->unsignedBigInteger('system_tenant_id');
    
                $table->foreign('database_cloud_id')->references('id')->on('database_clouds')->onDelete('cascade');
                $table->foreign('system_tenant_id')->references('id')->on('system_tenants')->onDelete('cascade');
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
        Schema::dropIfExists('database_cloud_tenant');
    }
};