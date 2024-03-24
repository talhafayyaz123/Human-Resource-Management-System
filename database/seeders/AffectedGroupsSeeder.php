<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Constants;
use App\Models\AffectedGroup;

class AffectedGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $affected_groups = Constants::AFFECTED_GROUPS;
        foreach ($affected_groups as $group) {
            //Create Affected Group
            $model = new AffectedGroup;
            $model->name = $group['name'];
            $model->save();
        }
    }
}
