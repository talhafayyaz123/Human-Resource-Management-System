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
        if (!Schema::hasTable('offer_requests')) {
            Schema::create('offer_requests', function (Blueprint $table) {
                $table->id();
                $table->string('offer_request_number')->nullable();
                $table->enum('receiver_type', ['customer', 'lead']);
                $table->foreignUuid('receiver_id')->references('id')->on('companies')->cascadeOnDelete();
                $table->longText('text');
                $table->enum('grouped_by', ['nothing', 'category'])->default('nothing');
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
        Schema::dropIfExists('offer_requests');
    }
};
