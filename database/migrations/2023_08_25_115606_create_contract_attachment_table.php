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
        Schema::create('contract_attachment', function (Blueprint $table) {
            if (!Schema::hasTable('contract_attachment')) {
                $table->id();
                $table->foreignId('contract_id')->references('id')->on('outbounded_contracts')->cascadeOnDelete();
                $table->foreignId('attachment_id')->references('id')->on('attachments')->cascadeOnDelete();
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_attachment');
    }
};
