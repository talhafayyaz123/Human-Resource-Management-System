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
        Schema::table('sla_infrastructures', function (Blueprint $table) {
            if (!Schema::hasColumn('sla_infrastructures', 'category') && !Schema::hasColumn('sla_infrastructures', 'price')) {
                $table->enum('category', ['key-user', 'end-user'])->default('key-user')->after('additional_user_cost');
                $table->decimal('price')->default(0)->after('additional_user_cost');
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
        Schema::table('sla_infrastructures', function (Blueprint $table) {
            if (Schema::hasColumn('sla_infrastructures', 'category') && Schema::hasColumn('sla_infrastructures', 'price')) {
                $table->dropColumn('category');
                $table->dropColumn('price');
            }
        });
    }
};
