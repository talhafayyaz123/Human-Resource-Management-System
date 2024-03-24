<?php

namespace Database\Seeders;

use App\Constants;
use App\Models\UserJobData;
use App\Models\UserProfileData;
use App\Models\UserWorkingHour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserProfileDataSeeder extends Seeder
{
    /**
     * The current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $working_days = [
            "mon",
            "tue",
            "wed",
            "thu",
            "fri",
        ];
        $this->faker = Faker::create();
        foreach (Constants::USER_PROFILE_DATA as $item) {
            $model = new UserProfileData;
            $model->first_name = $item['first_name'];
            $model->last_name = $item['last_name'];
            $model->email = $item['email'];
            $model->user_id = $item['user_id'];
            $model->save();

            $job_model = new UserJobData;
            $job_model->user_profile_id = $model->id;
            $model->user_id = $item['user_id'];
            $job_model->job_title = $this->faker->jobTitle();
            $job_model->total_annual_leaves = $this->faker->numberBetween(15, 20);
            $job_model->save();

            shuffle($working_days);
            $days = array_slice($working_days, 0, $this->faker->numberBetween(2, 5));
            $working_hours_array = [];
            $sum_of_hours = 0;
            foreach ($days as $day) {
                $daily_hours = $this->faker->numberBetween(7, 10);
                $working_hours_array[] = new UserWorkingHour(["day" => $day, "working_hours" => $daily_hours]);
                $sum_of_hours = $daily_hours + $sum_of_hours;
            }

            $job_model->workingHours()->saveMany($working_hours_array);

            $job_model->weekly_hours = $sum_of_hours;
            $job_model->save();
        }
    }
}
