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
        Schema::create('mail_template_assignments', function (Blueprint $table) {
            $table->id();
            $table->string("module");
            $table->string("mail_template_id")->nullable();
            $table->string("cc")->nullable();
            $table->string("bcc")->nullable();
            $table->string("sender_mail")->nullable();
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
        Schema::dropIfExists('mail_template_assignments');
    }
};
