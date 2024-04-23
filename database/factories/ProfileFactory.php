<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Base\User\Models\Profile>
 */
class ProfileFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'user_id' => User::factory(),
			'first_name' => fake()->firstName(),
			'last_name' => fake()->lastName(),
			'photo' => null,
			'dob' => fake()->dateTimeBetween('-50 years', '-20 years'),
			'country' => fake()->country(),
			'city' => fake()->city(),
			'address' => fake()->address(),
			'bio' => fake()->url,
			'contact' => fake()->phoneNumber(),
		];
	}
}
