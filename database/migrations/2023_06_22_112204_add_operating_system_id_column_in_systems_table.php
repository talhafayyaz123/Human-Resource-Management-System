<?php

use App\Models\OperatingSystem;
use App\Models\System;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            if (!Schema::hasColumn('systems', 'operating_system_id')) {
                $table->foreignId('operating_system_id')->nullable()->after('instance_type')->constrained('operating_systems')->cascadeOnDelete()->cascadeOnUpdate();
            }
        });

        //check if the system have already data exist in operating system then check value and pass new operating system id
        $systems = System::all();

        if($systems){
            //Add default entry to replace live data with their ids.
            $window = OperatingSystem::firstOrCreate(['name' => 'Windows'], ['name' => 'Windows']);
            $linux = OperatingSystem::firstOrCreate([ 'name' => 'Linux'], [ 'name' => 'Linux']);

            foreach ($systems as $row_sys) {
                if (!empty($row_sys->operating_system)) {
                    $row_sys->operating_system_id = $row_sys->operating_system === 'windows' ? $window->id : ($row_sys->operating_system === 'linux' ? $linux->id : '');
                    $row_sys->save();
                }
            }
        }

        // Also delete previous column of operating system
        Schema::table('systems', function (Blueprint $table) {
            if (Schema::hasColumn('systems', 'operating_system')) {
                $table->dropColumn('operating_system');
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
            $table->dropForeign(["operating_system_id"]);
            $table->dropColumn(["operating_system_id"]);
        });
    }
};
