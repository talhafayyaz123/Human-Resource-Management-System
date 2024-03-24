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
        Schema::table('contract_attachment', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_attachment', 'start_date')){
                $table->dateTime('start_date')->nullable();
            }
            if (!Schema::hasColumn('contract_attachment', 'signed_date')){
                $table->dateTime('signed_date')->nullable();
            }
            if (!Schema::hasColumn('contract_attachment', 'termination_date')){
                $table->dateTime('termination_date')->nullable();
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
        Schema::table('contract_attachment', function (Blueprint $table) {
            if (Schema::hasColumn('contract_attachment', 'start_date')){
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('contract_attachment', 'signed_date')){
                $table->dropColumn('signed_date');
            }
            if (Schema::hasColumn('contract_attachment', 'termination_date')){
                $table->dropColumn('termination_date');
            }
        });
    }
};
