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
        Schema::create('document_and_contracts', function (Blueprint $table) {
            $table->id();

            $table->string('stored_file_name');
            $table->string('file_name');
            $table->string('size')->nullable();

            $table->unsignedBigInteger('user_profile_id');
            $table->foreign('user_profile_id')->references('id')->on('user_profile_data')->onDelete('cascade');
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
        Schema::dropIfExists('document_and_contracts');
    }
};
