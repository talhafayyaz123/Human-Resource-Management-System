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
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('time')->nullable();
            $table->string('user_id');
            $table->string('user_type');
            $table->boolean('is_added')->default(false);
            $table->boolean('is_archived')->default(false);
            //Add foreign key relationship
            $table->unsignedBigInteger('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
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
        Schema::dropIfExists('ticket_comments');
    }
};
