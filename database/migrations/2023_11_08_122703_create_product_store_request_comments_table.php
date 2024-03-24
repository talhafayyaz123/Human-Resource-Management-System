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
        Schema::create('product_store_request_comments', function (Blueprint $table) {
            $table->id();
            $table->string('time')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_type');
            $table->boolean('is_added')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->enum('visibility', ['internal', 'public'])->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->longText('text')->nullable();
            //Add foreign key relationship
            $table->unsignedBigInteger('product_request_store_id')->references('id')->on('product_store_requests')->onDelete('cascade');
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
        Schema::dropIfExists('product_store_request_comments');
    }
};
