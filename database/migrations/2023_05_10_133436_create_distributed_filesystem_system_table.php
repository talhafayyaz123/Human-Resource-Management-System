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
        
        if(Schema::hasTable('distributed_filesystem_system')==false) 
        {
            Schema::create('distributed_filesystem_system', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('distributed_filesystem_id');
                $table->unsignedBigInteger('system_id');
                
                $table->foreign('distributed_filesystem_id')->references('id')->on('distributed_filesystems')->onDelete('cascade');
                $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');
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
        Schema::dropIfExists('distributed_filesystem_system');
    }
};
