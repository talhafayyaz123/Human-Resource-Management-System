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
        Schema::create('cloud_infrastructure_servers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('cluster_id');
            $table->boolean('is_master')->default(false);
            $table->string('server_pool_id');
            $table->foreign('cluster_id')->references('id')->on('cloud_infrastructures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cloud_infrastructure_servers');
    }
};
