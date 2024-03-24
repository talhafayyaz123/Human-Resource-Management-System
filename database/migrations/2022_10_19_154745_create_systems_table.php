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
        Schema::create('systems', function (Blueprint $table) {
            $table->id();

            $table->enum('type', ['premise', 'cloud', 'hosting']);
            $table->enum('instance_type', ['development', 'test', 'productive', 'database']);
            $table->enum('operating_system', ['windows', 'linux'])->nullable();

            //system_number
            $table->string('system_number');

            //system name
            $table->string('system_name')->nullable();

            //host id
            $table->unsignedBigInteger('system_host_id')->nullable()->references('id')->on('system_hosts')->onDelete('cascade');

            //On premise
            $table->string('server_ip')->nullable();
            $table->string('server_port')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();

            //cloud sub types
            $table->enum('sub_type', ['public', 'private'])->nullable();

            //Cloud
            $table->string('tenant_name')->nullable();
            //Hosting
            $table->string('host_virtual_machine')->nullable();
            //Private
            $table->string('namespace')->nullable();

            //Add foreign key relationship
            $table->string('customer_id')->nullable();

            //soft deletes addition
            $table->softDeletes();

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
        Schema::dropIfExists('systems');
    }
};
