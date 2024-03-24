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
        Schema::create('cancelation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('companies')->nullOnDelete();
            $table->foreignUuid('partner_id')->nullable()->references('id')->on('companies')->nullOnDelete();
            $table->longText('reason')->nullable();
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
        Schema::dropIfExists('cancelation_requests');
    }
};
