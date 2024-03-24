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
        if(Schema::hasTable('distributed_filesystem_servers')==false) 
        {
            Schema::create('distributed_filesystem_servers', function (Blueprint $table) {
                $table->id();
                $table->string('server_ip')->nullable();
                $table->string('server_port')->nullable();
                $table->string('username')->nullable();
                $table->string('password')->nullable();
                $table->boolean('is_master')->nullable();
                $table->unsignedBigInteger("distributed_filesystem_id");
                $table->foreign("distributed_filesystem_id")->references("id")->on("distributed_filesystems")->onDelete("cascade");
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
        Schema::dropIfExists('distributed_filesystem_servers');
    }
};
