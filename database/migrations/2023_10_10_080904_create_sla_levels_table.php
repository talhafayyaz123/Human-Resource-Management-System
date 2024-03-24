<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\SlaLevel;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            DB::transaction(function () {
                if (!Schema::hasTable('sla_levels')) {
                    Schema::create('sla_levels', function (Blueprint $table) {
                        $table->id();
                        $table->string('name_urgent')->nullable();
                        $table->string('name_high')->nullable();
                        $table->string('name_low')->nullable();
                        $table->decimal('reaction_time_urgent')->nullable();
                        $table->decimal('reaction_time_high')->nullable();
                        $table->decimal('reaction_time_low')->nullable();
                        $table->decimal('factor')->nullable();
                        $table->timestamps();
                    });
                    $sla_level_changes = DB::table('sla_level_changes')->get();

                    foreach ($sla_level_changes as $level) {
                        $name_urgent = null;
                        $name_high = null;
                        $name_low = null;
                        $reaction_time_urgent = null;
                        $reaction_time_high = null;
                        $reaction_time_low = null;
                        if ($level->priority == 0) {
                            $name_urgent = $level->name;
                            $reaction_time_urgent = $level->change;
                        } else if ($level->priority == 1) {
                            $name_high = $level->name;
                            $reaction_time_high = $level->change;
                        } else if ($level->priority == 2) {
                            $name_low = $level->name;
                            $reaction_time_low = $level->change;
                        }
                        SlaLevel::create([
                            'name_urgent' => $name_urgent,
                            'name_high' => $name_high,
                            'name_low' => $name_low,
                            'reaction_time_urgent' => $reaction_time_urgent,
                            'reaction_time_high' => $reaction_time_high,
                            'reaction_time_low' => $reaction_time_low,
                            'factor' => $level->factor,
                        ]);
                    }
                    $sla_level_incidents = DB::table('sla_level_incidents')->get();
                    foreach ($sla_level_incidents as $level) {
                        $name_urgent = null;
                        $name_high = null;
                        $name_low = null;
                        $reaction_time_urgent = null;
                        $reaction_time_high = null;
                        $reaction_time_low = null;
                        if ($level->priority == 0) {
                            $name_urgent = $level->name;
                            $reaction_time_urgent = $level->incident;
                        } else if ($level->priority == 1) {
                            $name_high = $level->name;
                            $reaction_time_high = $level->incident;
                        } else if ($level->priority == 2) {
                            $name_low = $level->name;
                            $reaction_time_low = $level->incident;
                        }
                        SlaLevel::create([
                            'name_urgent' => $name_urgent,
                            'name_high' => $name_high,
                            'name_low' => $name_low,
                            'reaction_time_urgent' => $reaction_time_urgent,
                            'reaction_time_high' => $reaction_time_high,
                            'reaction_time_low' => $reaction_time_low,
                            'factor' => $level->factor,
                        ]);
                    }
                }
            });
        } catch (\Exception $e) {
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sla_levels');
    }
};
