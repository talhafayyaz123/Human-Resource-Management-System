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
            if (!Schema::hasColumn('sale_offers', 'contact_person')) {
                $table->string('contact_person')->nullable();
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
        Schema::table('sale_offers', function (Blueprint $table) {
            $table->dropColumn('contact_person');
        });
    }
};
