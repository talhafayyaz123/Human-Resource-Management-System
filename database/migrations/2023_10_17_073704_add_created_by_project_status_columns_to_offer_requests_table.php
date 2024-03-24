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
            if (!Schema::hasColumn('offer_requests', 'created_by')) {
                $table->foreignId('created_by')->references('id')->on('user_profile_data')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('offer_requests', 'project_id')) {
                $table->foreignId('project_id')->nullable()->references('id')->on('projects')->nullOnDelete();
            }
            if (!Schema::hasColumn('offer_requests', 'status')) {
                $table->enum('status', ['open', 'approved', 'declined'])->default('open');
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
        Schema::table('offer_requests', function (Blueprint $table) {
            if (Schema::hasColumn('offer_requests', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('offer_requests', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
            if (Schema::hasColumn('offer_requests', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
