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
        Schema::table('contract_field_products', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_field_products', 'type')){
                $table->string('type')->after('contract_field_id')->nullable();
            }
            if (!Schema::hasColumn('contract_field_products', 'maintenance_rate')){
                $table->decimal('maintenance_rate')->after('maintenance_price')->nullable();
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
        Schema::table('contract_field_products', function (Blueprint $table) {
            if (Schema::hasColumn('contract_field_products', 'type')){
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('contract_field_products', 'maintenance_rate')){
                $table->dropColumn('maintenance_rate');
            }
        });
    }
};
