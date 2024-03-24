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
        Schema::table('contract_types', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_types', 'allow_to_add_slas')) {
                $table->boolean('allow_to_add_slas')->default(0);
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
        Schema::table('contract_types', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_types', 'allow_to_add_slas')) {
                $table->dropColumn('allow_to_add_slas');
            }
        });
    }
};
