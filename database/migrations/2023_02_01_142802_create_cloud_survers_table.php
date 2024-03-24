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
        Schema::create('cloud_servers', function (Blueprint $table) {
            $table->id();
            $table->string('server_ip');
            $table->string('port')->default(22);
            $table->string('user_address');
            $table->string('password');

            $table->unsignedBigInteger("system_id");
            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');
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
        Schema::dropIfExists('cloud_servers');
    }
};
