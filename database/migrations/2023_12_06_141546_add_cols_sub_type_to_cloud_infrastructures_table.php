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
        Schema::table('cloud_infrastructures', function (Blueprint $table) {
            //
            $table->enum('sub_type', ['public', 'private'])->nullable()->after('instance_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cloud_infrastructures', function (Blueprint $table) {
            // Drop the 'sub_type' column
            $table->dropColumn('sub_type');
        });
    }
};