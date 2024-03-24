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
        Schema::table('systems', function (Blueprint $table) {
            if (!Schema::hasColumn('systems', 'product_software_id')) {
                $table->foreignId('product_software_id')->nullable()->after('operating_system_id')->constrained('product_software')->cascadeOnDelete()->cascadeOnUpdate();
            }
            if (!Schema::hasColumn('systems', 'elo_version_id')) {
                $table->foreignId('elo_version_id')->nullable()->after('product_software_id')->constrained('elo_versions')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::table('systems', function (Blueprint $table) {
            if (Schema::hasColumn('systems', 'product_software_id')) {
                $table->dropForeign(["product_software_id"]);
                $table->dropColumn('product_software_id');
            }
            if (Schema::hasColumn('systems', 'elo_version_id')) {
                $table->dropForeign(["elo_version_id"]);
                $table->dropColumn('elo_version_id');
            }
        });
    }
};
