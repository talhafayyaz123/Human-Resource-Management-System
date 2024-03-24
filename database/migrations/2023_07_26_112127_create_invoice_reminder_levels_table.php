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
        Schema::create('invoice_reminder_levels', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('level_name');
            $table->integer('period_days');
            $table->double('reminder_fee');
            $table->longText('start_text');
            $table->longText('end_text');
            $table->longText('mail_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_reminder_levels');
    }
};
