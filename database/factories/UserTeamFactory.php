<?php

namespace Database\Factories;

use App\Models\UserDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserTeam>
 */
class UserTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user_team_lead_ids = [
            "1867617a-9ffa-ff35-025a-42baa91ca4d3",
            "186762e7-09dc-7f10-0139-5289c074f83a",
        ];

        $user_team_lead_id = $user_team_lead_ids[(array_rand($user_team_lead_ids))];

        return [
            'name' => $this->faker->jobTitle(),
            'team_lead_id' => $user_team_lead_id,
            'department_id' => UserDepartment::inRandomOrder()->first()->id,
        ];
    }
}
