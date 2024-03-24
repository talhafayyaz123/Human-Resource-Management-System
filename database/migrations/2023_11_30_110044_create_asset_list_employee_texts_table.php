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
        Schema::create('asset_list_employee_texts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //foreign key relationships
            $table->unsignedBigInteger('asset_list_id');
            $table->foreign('asset_list_id')->references('id')->on('asset_lists')->onDelete('cascade');

            $table->unsignedBigInteger('employee_text_id');
            $table->foreign('employee_text_id')->references('id')->on('asset_employee_list_texts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_list_employee_texts');
    }
};
