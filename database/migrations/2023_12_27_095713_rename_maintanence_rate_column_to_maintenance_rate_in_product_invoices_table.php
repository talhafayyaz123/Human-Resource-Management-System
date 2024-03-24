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
        Schema::table('product_invoice', function (Blueprint $table) {
            if (Schema::hasColumn('product_invoice', 'maintanence_rate')) {
                $table->renameColumn('maintanence_rate', 'maintenance_rate');
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
        Schema::table('product_invoice', function (Blueprint $table) {
            if (Schema::hasColumn('product_invoice', 'maintenance_rate')) {
                $table->renameColumn('maintenance_rate', 'maintanence_rate');
            }
        });
    }
};
