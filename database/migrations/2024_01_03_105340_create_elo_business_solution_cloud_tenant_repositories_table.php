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
        Schema::create('elo_business_solution_cloud_tenant_repositories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('cloud_tenant_repository_id');
            $table->unsignedBigInteger('elo_business_solution_id');
            $table->foreign('cloud_tenant_repository_id', 'cloud_tenant_repository_foreign')->references('id')->on('cloud_infrastructure_tenant_repositories')->onDelete('cascade');
            $table->foreign('elo_business_solution_id', 'elo_business_solution_foreign')->references('id')->on('elo_business_solutions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elo_business_solution_cloud_tenant_repositories');
    }
};
