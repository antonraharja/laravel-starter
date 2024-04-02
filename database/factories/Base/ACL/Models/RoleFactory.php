<?php

namespace Database\Factories\Base\ACL\Models;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Base\ACL\Models\Role>
 */
class RoleFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'name' => fake()->colorName(),
			'description' => fake()->sentences(),
		];
	}

	public function modelName()
	{
		return \Base\ACL\Models\Role::class;
	}
}
