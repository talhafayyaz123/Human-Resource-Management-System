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
        if (!Schema::hasTable('project_protocol_description_entries')) {
            Schema::create('project_protocol_description_entries', function (Blueprint $table) {
                $table->id();
                $table->string('description');
                $table->dateTime('expiry_date');
                $table->string('ownership'); // auth user ID
                // one to many relation with project_protocols
                $table->unsignedBigInteger('project_protocol_id');
                $table->foreign('project_protocol_id')->references('id')->on('project_protocols')->onDelete('cascade');
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
        Schema::dropIfExists('project_protocol_description_entries');
    }
};
