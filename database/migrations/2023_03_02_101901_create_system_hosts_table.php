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
        Schema::create('system_hosts', function (Blueprint $table) {
            $table->id();
            $table->string('server_ip');
            $table->string('server_port');
            $table->string('username');
            $table->string('password');
            $table->string('machine_name');
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
        Schema::dropIfExists('system_hosts');
    }
};
