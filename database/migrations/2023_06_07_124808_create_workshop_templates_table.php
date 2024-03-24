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
        if(Schema::hasTable('workshop_templates')==false) 
        {
            Schema::create('workshop_templates', function (Blueprint $table) {
                $table->id();
                $table->string("company_id");
                $table->unsignedBigInteger("system_id");
                $table->unsignedBigInteger("consultant")->nullable();

                $table->foreign("company_id")->references("id")->on("companies")->onDelete("cascade");
                $table->foreign("system_id")->references("id")->on("systems")->onDelete("cascade");
                $table->foreign("consultant")->references("id")->on("user_profile_data")->onDelete("set null");
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
        Schema::dropIfExists('workshop_templates');
    }
};
