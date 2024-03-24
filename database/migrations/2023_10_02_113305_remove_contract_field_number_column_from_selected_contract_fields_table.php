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
        Schema::table('selected_contract_fields', function (Blueprint $table) {
            if (Schema::hasColumn('selected_contract_fields', 'contract_field_number')) {
                $table->dropColumn('contract_field_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selected_contract_fields', function (Blueprint $table) {
            if (!Schema::hasColumn('selected_contract_fields', 'contract_field_number')) {
                $table->string('contract_field_number')->nullable();
            }
        });
    }
};
