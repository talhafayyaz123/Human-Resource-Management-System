<?php

use App\Models\HighestEducationLevel;
use App\Models\HighestSchoolDegree;
use App\Models\UserProfileData;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user_profile_data = UserProfileData::get();
        foreach ($user_profile_data as $data) {
            if (isset($data->highest_school_degree)) {
                $highest_school_degree = HighestSchoolDegree::firstOrNew(['name' => $data->highest_school_degree]);
                $highest_school_degree->save();
                $data->highest_school_degree_id = $highest_school_degree->id;
            }
            if (isset($data->highest_education_level)) {
                $highest_education_level = HighestEducationLevel::firstOrNew(['name' => $data->highest_education_level]);
                $highest_education_level->save();
                $data->highest_education_level_id = $highest_education_level->id;
            }
            $data->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
