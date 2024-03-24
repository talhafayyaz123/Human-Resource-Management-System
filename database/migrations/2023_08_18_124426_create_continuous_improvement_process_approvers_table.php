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
        Schema::create('continuous_improvement_process_approvers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cip_id')->references('id')->on('continuous_improvement_process')->cascadeOnDelete();
            $table->foreignId('approver_id')->references('id')->on('user_profile_data')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default("pending");
            $table->enum('approver_type', ['cip-manager', 'quality-management-officer', 'executive-board'])->default("cip-manager");
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
        Schema::dropIfExists('continuous_improvement_process_approvers');
    }
};
