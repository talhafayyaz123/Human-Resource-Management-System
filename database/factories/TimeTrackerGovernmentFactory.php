<?php

namespace Database\Factories;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeTrackerGovernment>
 */
class TimeTrackerGovernmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user_ids = [
            "1867617a-9ffa-ff35-025a-42baa91ca4d3",
            "186762e7-09dc-7f10-0139-5289c074f83a",
            "18678043-924f-54c7-0350-c636d2098967",
            "1867f9b4-983f-f721-0276-462cc3a77712",
            "18683757-971d-27c8-022d-f0a9e7f4d192",
            "18683771-0199-6c8b-026d-4bb81a4d7e51",
            "18683782-1a3f-f539-023e-97b7311e6cbc",
        ];

        $user_id = $user_ids[(array_rand($user_ids))];
        $time = $this->faker->numberBetween(7, 10);
        $date = $this->faker->unique()->dateTimeThisYear();
        $start_time = Carbon::parse('9:00')->toTimeString();
        $end_time = Carbon::parse('9:00')->addHours($time)->toTimeString();

        return [
            "date" => $date,
            "type" => $this->faker->randomElement(['ticket', 'task']),
            "description" => "testcase",
            "start_time" => $start_time,
            "end_time" => $end_time,
            "user_id" => $user_id,
        ];
    }
}
