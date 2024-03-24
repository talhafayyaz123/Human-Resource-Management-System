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
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn("companies", "is_customer_portal_onboarding")) {
                $table->boolean('is_customer_portal_onboarding')->default(false);
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
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn("companies", "is_customer_portal_onboarding")) {
                $table->dropColumn('is_customer_portal_onboarding');
            }
        });
    }
};
