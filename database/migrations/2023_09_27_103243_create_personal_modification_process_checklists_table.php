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
        Schema::create('personal_modification_process_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->enum('process', ['name-change', 'bank-account-change', 'change-of-health-insurance-company', 'change-of-address', 'change-of-tax-class', 'change-of-child-allowance']);
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
        Schema::dropIfExists('personal_modification_process_checklists');
    }
};
