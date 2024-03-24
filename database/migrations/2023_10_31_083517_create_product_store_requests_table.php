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
        Schema::create('product_store_requests', function (Blueprint $table) {
            $table->id();
            $table->date('creation_date');
            $table->string('item_number')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('short_description')->nullable();
            $table->decimal('sell_price', 16,2)->nullable();
            $table->string('author_id');
            $table->enum('status', ['Draft', 'Pending', 'Accepted', 'Declined'])->default('Draft');
            $table->longText('approver_notes');
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
        Schema::dropIfExists('product_store_requests');
    }
};
