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
        Schema::table('contract_attachment', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_attachment', 'attachment_number')) {
                $table->string('attachment_number')->nullable();
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
        Schema::table('contract_attachment', function (Blueprint $table) {
            if (Schema::hasColumn('contract_attachment', 'attachment_number')) {
                $table->dropColumn('attachment_number');
            }
        });
    }
};
