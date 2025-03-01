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
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['supplier']);
            $table->string('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            // Remove the foreign key constraint
            $table->dropForeign(['supplier_id']);

            // Drop the 'supplier_id' column
            $table->dropColumn('supplier_id');

            // Re-add the 'supplier' column
            $table->string('supplier');
        });
    }
};
