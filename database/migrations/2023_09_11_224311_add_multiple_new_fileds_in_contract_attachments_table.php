<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_attachment', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_attachment', 'is_fixed_price')) {
                $table->boolean('is_fixed_price')->default(0);
            }

            if (!Schema::hasColumn('contract_attachment', 'hourly_rate')) {
                $table->decimal('hourly_rate')->nullable();
            }

            if (!Schema::hasColumn('contract_attachment', 'hours_per_year')) {
                $table->decimal('hours_per_year')->nullable();
            }

            if (!Schema::hasColumn('contract_attachment', 'total_price')) {
                $table->decimal('total_price')->nullable();
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
            if (Schema::hasColumn('contract_attachment', 'is_fixed_price')) {
                $table->dropColumn('is_fixed_price');
            }

            if (Schema::hasColumn('contract_attachment', 'hourly_rate')) {
                $table->dropColumn('hourly_rate');
            }

            if (Schema::hasColumn('contract_attachment', 'hours_per_year')) {
                $table->dropColumn('hours_per_year');
            }

            if (Schema::hasColumn('contract_attachment', 'total_price')) {
                $table->dropColumn('total_price');
            }
        });
    }
};
