<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 
        Schema::table('companies', function (Blueprint $table) {
            DB::statement("ALTER TABLE companies MODIFY COLUMN type enum('premise', 'private', 'public','hosting','other') NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('companies', function (Blueprint $table) {
            DB::statement("ALTER TABLE companies MODIFY COLUMN type enum('premise', 'private', 'public') NOT NULL");
        });
    }
};
