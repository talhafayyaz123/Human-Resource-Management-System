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
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'customer_type')){
                $table->string('customer_type')->default('customer')->comment('customer/partner');
            }
        });

        $allTickets = \App\Models\Ticket::query()->update(['customer_type' => 'customer']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'customer_type')){
                $table->dropColumn('customer_type');
            }
        });
    }
};
