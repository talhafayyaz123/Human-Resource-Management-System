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
        Schema::table('travel_expense_report_transportations', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_expense_report_transportations', 'car_type')) {
                $table->enum('car_type', ['private-car', 'fleet-car'])->default('private-car');
            }
            if (!Schema::hasColumn('travel_expense_report_transportations', 'fleet_car_id')) {
                $table->foreignId('fleet_car_id')->nullable()->references('id')->on('fleet_cars')->nullOnDelete();
            }
            if (!Schema::hasColumn('travel_expense_report_transportations', 'amount')) {
                $table->decimal('amount')->default(0);
            }
            if (Schema::hasColumn('travel_expense_report_transportations', 'license_number')) {
                $table->dropColumn('license_number');
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
        Schema::table('travel_expense_report_transportations', function (Blueprint $table) {
            if (Schema::hasColumn('travel_expense_report_transportations', 'car_type')) {
                $table->dropColumn('car_type');
            }
            if (Schema::hasColumn('travel_expense_report_transportations', 'fleet_car_id')) {
                $table->dropForeign(['fleet_car_id']);
                $table->dropColumn(['fleet_car_id']);
            }
            if (Schema::hasColumn('travel_expense_report_transportations', 'amount')) {
                $table->dropColumn('amount');
            }
            if (!Schema::hasColumn('travel_expense_report_transportations', 'license_number')) {
                $table->string('license_number');
            }
        });
    }
};
