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
        Schema::create('department_parent_child', function (Blueprint $table) {
            $table->id();
            //foreign key constraints
            $table->foreignUuid('parent_department_id')
                ->constrained('user_departments')
                ->onDelete('cascade');
            $table->foreignUuid('child_department_id')
                ->constrained('user_departments')
                ->onDelete('cascade');
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
        Schema::dropIfExists('department_parent_child');
    }
};
