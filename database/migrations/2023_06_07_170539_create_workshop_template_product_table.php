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
        if (!Schema::hasTable("workshop_template_product")) {
            Schema::create('workshop_template_product', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger("workshop_template_id");
                $table->unsignedBigInteger("product_id");
                $table->foreign("workshop_template_id")->references("id")->on("workshop_templates")->onDelete("cascade");
                $table->foreign("product_id")->references("id")->on("products")->onDelete("cascade");
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
        Schema::dropIfExists('workshop_template_product');
    }
};
