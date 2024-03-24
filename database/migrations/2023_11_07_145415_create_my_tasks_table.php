<?php

use App\Enums\TaskType;
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
        Schema::create('my_tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('icon')->nullable();
            $table->string('color');
            $table->string('headline');
            $table->string('priority');
            $table->timestamp('due_date')->nullable();
            $table->enum('task_type', TaskType::ALL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('my_tasks');
    }
};
