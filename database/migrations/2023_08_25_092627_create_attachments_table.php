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
        Schema::create('attachments', function (Blueprint $table) {
            if (!Schema::hasTable('attachments')) {
                $table->id();
                $table->string('name');
                $table->string('template');
                $table->string('version');
                $table->string('prefix');
                $table->foreignId('software_id')->references('id')->on('product_software')->cascadeOnDelete();
                $table->foreignId('contract_type_id')->references('id')->on('contract_types')->cascadeOnDelete();
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
        Schema::dropIfExists('attachments');
    }
};
