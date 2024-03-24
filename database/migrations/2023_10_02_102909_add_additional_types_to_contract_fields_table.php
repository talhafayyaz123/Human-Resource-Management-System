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
        Schema::table('contract_fields', function (Blueprint $table) {
            Schema::table('contract_fields', function (Blueprint $table) {
                DB::statement("ALTER TABLE contract_fields MODIFY COLUMN type enum('text', 'number', 'date', 'time', 'customer', 'offer', 'invoice', 'performance-record', 'products')");
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_fields', function (Blueprint $table) {
            Schema::table('contract_fields', function (Blueprint $table) {
                DB::statement("ALTER TABLE contract_fields MODIFY COLUMN type enum('text', 'number', 'date', 'time')");
            });
        });
    }
};
