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
        if(Schema::hasTable('database_cloud_servers')==false) 
        {
            Schema::create('database_cloud_servers', function (Blueprint $table) {
                $table->id();
                $table->string('server_ip')->nullable();
                $table->string('server_port')->nullable();
                $table->string('username')->nullable();
                $table->string('password')->nullable();
                $table->unsignedBigInteger("database_cloud_id");
                $table->foreign("database_cloud_id")->references("id")->on("database_clouds")->onDelete("cascade");
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
        Schema::dropIfExists('database_cloud_servers');
    }
};
