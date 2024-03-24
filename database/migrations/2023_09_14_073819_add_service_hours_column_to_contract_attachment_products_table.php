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
        Schema::table('contract_attachment_products', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_attachment_products', 'service_hours')) {
                $table->decimal('service_hours')->after("sla_level_change_id")->nullable();
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
        Schema::table('contract_attachment_products', function (Blueprint $table) {
            if (Schema::hasColumn('contract_attachment_products', 'service_hours')) {
                $table->dropColumn('service_hours');
            }
        });
    }
};
