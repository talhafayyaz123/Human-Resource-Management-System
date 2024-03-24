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
        Schema::create('asset_deliveries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('quantity');

            $table->string('delivery_note_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->boolean('is_delivery_checked')->default(false);
            //foreign key constraints
            $table->unsignedBigInteger('storage_location_id')->nullable();
            $table->string('supplier_id')->nullable();
            $table->foreign('storage_location_id')->references('id')->on('storage_locations')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->foreign("asset_id")->references("id")->on("assets")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_deliveries');
    }
};
