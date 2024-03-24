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
        Schema::create('selected_contract_fields', function (Blueprint $table) {
            if (!Schema::hasTable('selected_contract_fields')) {
                $table->id();
                $table->string('contract_field_number')->nullable();
                $table->foreignId('contract_id')->references('id')->on('outbounded_contracts')->cascadeOnDelete();
                $table->foreignId('contract_field_id')->references('id')->on('contract_fields')->cascadeOnDelete();
                $table->string('text')->nullable();
                $table->decimal('number')->nullable();
                $table->dateTime('date')->nullable();
                $table->time('time')->nullable();
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
        Schema::dropIfExists('selected_contract_fields');
    }
};
