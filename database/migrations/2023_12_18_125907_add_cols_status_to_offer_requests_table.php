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
        Schema::table('offer_requests', function (Blueprint $table) {
            //
            $table->enum('request_status', ['draft', 'open', 'pending', 'rejected', 'approved'])->after('grouped_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_requests', function (Blueprint $table) {
            //
            if (Schema::hasColumn('offer_requests', 'request_status')) {
                $table->dropColumn('request_status');
            }
        });
    }
};