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
        if (!Schema::hasTable('informed_ticket_comment_users')) {
            Schema::create('informed_ticket_comment_users', function (Blueprint $table) {
                $table->id();
                $table->string('user_id');
                $table->foreignId('ticket_comment_id')->references('id')->on('ticket_comments')->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('informed_ticket_comment_users');
    }
};
