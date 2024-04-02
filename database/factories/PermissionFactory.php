<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'name' => 'ADMIN_DEFAULT_POLICY',
			'description' => 'Default permissions for Administrator',
			'type' => 'POLICY',
			'content' => [
				'user',
				'role',
				'permission',
				'token',
			]
		];
	}
}
