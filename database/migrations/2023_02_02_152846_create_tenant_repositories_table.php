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
        Schema::create('tenant_repositories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('text');
            $table->unsignedBigInteger("tenant_id");
            $table->foreign('tenant_id')->references('id')->on('system_tenants')->onDelete('cascade');
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
        Schema::dropIfExists('tenant_repositories');
    }
};
