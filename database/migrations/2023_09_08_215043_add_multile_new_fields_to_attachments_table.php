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
        Schema::table('attachments', function (Blueprint $table) {
            if (!Schema::hasColumn('attachments', 'runtime_in')) {
                $table->integer('runtime_in')->default(0)->nullable();
            }
            if (!Schema::hasColumn('attachments', 'renewal_period')) {
                $table->boolean('renewal_period')->default(0)->nullable();
            }
            if (!Schema::hasColumn('attachments', 'renewal_period_in')) {
                $table->integer('renewal_period_in')->default(0)->nullable();
            }
            if (!Schema::hasColumn('attachments', 'contract_end_at')) {
                $table->date('contract_end_at')->nullable();
            }
            if (!Schema::hasColumn('attachments', 'notice_period_in')) {
                $table->integer('notice_period_in')->default(0)->nullable();
            }
            if (!Schema::hasColumn('attachments', 'right_of_termination')) {
                $table->boolean('right_of_termination')->default(0)->nullable();
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
        Schema::table('attachments', function (Blueprint $table) {
            if (Schema::hasColumn('attachments', 'runtime_in')) {
                $table->dropColumn('runtime_in');
            }
            if (Schema::hasColumn('attachments', 'renewal_period')) {
                $table->dropColumn('renewal_period');
            }
            if (Schema::hasColumn('attachments', 'renewal_period_in')) {
                $table->dropColumn('renewal_period_in');
            }
            if (Schema::hasColumn('attachments', 'contract_end_at')) {
                $table->dropColumn('contract_end_at');
            }
            if (Schema::hasColumn('attachments', 'notice_period_in')) {
                $table->dropColumn('notice_period_in');
            }
            if (Schema::hasColumn('attachments', 'right_of_termination')) {
                $table->dropColumn('right_of_termination');
            }
        });
    }
};
