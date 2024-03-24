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
        Schema::create('asset_articles', function (Blueprint $table) {
            $table->id();
            $table->string('article_number');
            $table->unsignedBigInteger('asset_id');

            $table->string('model');
            $table->string('serial_no');

            $table->timestamp('delivery_date');
            $table->text('storage_location')->nullable();

            $table->enum('inventory_status', ['stock', 'assigned'])->nullable();
            $table->string('user_id')->nullable();

            //foreign key constraints
            $table->foreign("asset_id")->references("id")->on("assets")->onDelete("cascade");
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
        Schema::dropIfExists('asset_articles');
    }
};
