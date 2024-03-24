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
        Schema::table('outbounded_contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('outbounded_contracts', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('outbounded_contracts', 'person_in_charge')) {
                $table->foreignId('person_in_charge')->nullable()->references('id')->on('user_profile_data')->nullOnDelete();
            }
            if (!Schema::hasColumn('outbounded_contracts', 'termination_date')) {
                $table->date('termination_date')->nullable();
            }
            if (Schema::hasColumn('outbounded_contracts', 'status')) {
                $table->string('status')->nullable()->change();
            } else {
                $table->string('status')->nullable();
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
        Schema::table('outbounded_contracts', function (Blueprint $table) {
            if (Schema::hasColumn('outbounded_contracts', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('outbounded_contracts', 'person_in_charge')) {
                $table->dropForeign(['person_in_charge']);
                $table->dropColumn('person_in_charge');
            }
            if (Schema::hasColumn('outbounded_contracts', 'termination_date')) {
                $table->dropColumn('termination_date');
            }
            if (Schema::hasColumn('outbounded_contracts', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
