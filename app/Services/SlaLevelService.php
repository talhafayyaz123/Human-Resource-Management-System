<?php

namespace App\Services;

use App\Models\SlaLevel;

class SlaLevelService
{
    /**
     * Create a new sla_level.
     *
     * @param  array  $data
     * @return \App\Models\SlaLevel
     */
    public function createSlaLevel(array $data)
    {
        $sla_level = SlaLevel::create($data);
        return $sla_level;
    }

    /**
     * Update the specified sla_level.
     *
     * @param  \App\Models\SlaLevel  $sla_level
     * @param  array  $data
     * @return \App\Models\SlaLevel
     */
    public function updateSlaLevel(SlaLevel $sla_level, array $data)
    {
        $sla_level->update($data);
        return $sla_level;
    }

    /**
     * Delete the specified sla_level.
     *
     * @param  \App\Models\SlaLevel  $sla_level
     * @return void
     */
    public function deleteSlaLevel(SlaLevel $sla_level)
    {
        $sla_level->delete();
    }
}
