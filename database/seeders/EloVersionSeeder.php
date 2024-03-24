<?php

namespace Database\Seeders;

use App\Constants;
use App\Models\EloVersion;
use Illuminate\Database\Seeder;

class EloVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $elo_versions = Constants::ELO_VERSION;
        foreach ($elo_versions as $version) {
            //Create Elo version Group
            $model = new EloVersion;

            $model->name = $version['name'];
            $model->type = $version['type'];
            $model->save();
        }
    }
}
