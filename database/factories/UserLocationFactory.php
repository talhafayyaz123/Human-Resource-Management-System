<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'street' => $this->faker->streetName(),
            'address' => $this->faker->address(),
            'zip_code' => $this->faker->postcode(),
            'state' => $this->faker->state(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country()
        ];
    }
}
