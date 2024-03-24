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
        if(Schema::hasTable('workshop_templates_cards')==false) 
        {
            Schema::create('workshop_templates_cards', function (Blueprint $table) {
                $table->id();
                $table->json("configuration")->nullable();
                $table->unsignedBigInteger("workshop_template_id");
                $table->foreign("workshop_template_id")->references("id")->on("workshop_templates")->onDelete("cascade");
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
        Schema::dropIfExists('workshop_templates_cards');
    }
};
