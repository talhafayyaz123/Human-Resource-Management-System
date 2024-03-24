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
        if (!Schema::hasTable("continuous_improvement_process")) {
            Schema::create("continuous_improvement_process", function (Blueprint $table) {
                $table->id();
                $table->string("request_number");
                $table->string("process_number")->nullable();
                $table->string("title");
                $table->dateTime("date");
                $table->string("description");
                $table->string("suggested_solution");
                $table->unsignedBigInteger("affected_group_id")->nullable();
                $table->foreign("affected_group_id")->nullable()->references("id")->on("affected_groups")->onDelete("set null");
                $table->unsignedBigInteger("user_id");
                $table->foreign("user_id")->references("id")->on("user_profile_data")->onDelete("cascade");
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
        Schema::dropIfExists("continuous_improvement_process");
    }
};
