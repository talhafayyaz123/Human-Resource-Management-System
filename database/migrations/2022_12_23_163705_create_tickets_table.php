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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('ticket_number');
            $table->string('state', 20)->default("new");
            $table->string('priority', 20)->default("low");
            $table->string('contact_type', 20);

            $table->string('user_id');
            $table->string('assignee');
            $table->string('user_type');

            $table->boolean('is_archived')->default(false);

            //Add foreign key relationship
            $table->string('company_id')->nullable()->references('id')->on('companies')->onDelete('set null');

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
        Schema::dropIfExists('tickets');
    }
};
