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
        Schema::table('sale_offers', function (Blueprint $table) {
            $table->enum('type',['offer','order'])->after('id')->default('offer')
            ->comment('To differentiate between offer and order confirmation entries');
            $table->string('order_status')->default('draft')->after('contact_person')
            ->comment('Only use for order confirmation module');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_offers', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('order_status');
        });
    }
};
