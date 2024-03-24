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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dateTime('due_date')->change();
            $table->dateTime('start_date')->nullable()->change();
            $table->dateTime('end_date')->nullable()->change();
            $table->dateTime('draft_status_change_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            DB::statement("ALTER TABLE invoices MODIFY due_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
            DB::statement("ALTER TABLE invoices MODIFY start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
            DB::statement("ALTER TABLE invoices MODIFY end_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
            DB::statement("ALTER TABLE invoices MODIFY draft_status_change_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        });
    }
};
