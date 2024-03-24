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
        if(Schema::hasTable('database_clouds')==false) 
        {
            Schema::create('database_clouds', function (Blueprint $table) {
                $table->id();
                $table->string("name");
                $table->enum("sub_type", ["public", "private"]);
                $table->enum("instance_type", ["development", "test", "productive"]);
                $table->string("database_type");
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
        Schema::dropIfExists('table_database_cloud');
    }
};
