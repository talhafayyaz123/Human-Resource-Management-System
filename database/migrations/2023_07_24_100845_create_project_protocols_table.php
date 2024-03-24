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
        if (!Schema::hasTable('project_protocols')) {
            Schema::create('project_protocols', function (Blueprint $table) {
                $table->id();
                $table->dateTime("date");
                $table->string("recorder_id"); // auth user ID
                // one to many relation with companies
                $table->string("customer_id");
                $table->foreign("customer_id")->references("id")->on("companies")->onDelete("cascade");
                $table->json('distributors'); // array of auth user IDs
                $table->json('participants'); // array of auth user IDs
                // one to many relation with protocols_types
                $table->unsignedBigInteger("protocol_type_id");
                $table->foreign("protocol_type_id")->references("id")->on("protocol_types")->onDelete("cascade");
                // one to many relation with projects
                $table->unsignedBigInteger("project_id");
                $table->foreign("project_id")->references("id")->on("projects")->onDelete("cascade");
                // one to many relation with project_statuses
                $table->unsignedBigInteger("project_status_id");
                $table->foreign("project_status_id")->references("id")->on("project_statuses")->onDelete("cascade");
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
        Schema::dropIfExists('project_protocols');
    }
};
