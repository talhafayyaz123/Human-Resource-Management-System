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
        Schema::table('partner_orders', function (Blueprint $table) {
            $table->string('partner_contact_person')->nullable()->after('contact_person');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner_orders', function (Blueprint $table) {
            $table->dropColumn('partner_contact_person');
        });
    }
};
