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
        Schema::create('personal_request_approvers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approver_id')->references('id')->on('user_profile_data')->cascadeOnDelete();
            $table->enum('type', ['manager', 'management', 'executive-management']);
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
        Schema::dropIfExists('personal_request_approvers');
    }
};
