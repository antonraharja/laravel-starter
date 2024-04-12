<?php

namespace Database\Factories\Base\ACL\Models;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Base\ACL\Models\Permission>
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
			'name' => 'ADMIN_DEFAULT_BUNDLE',
			'description' => 'Default permissions for Administrator',
			'type' => 'BUNDLE',
			'content' => config('acl.bundles')
		];
	}

	public function modelName()
	{
		return \Base\ACL\Models\Permission::class;
	}

	// public static function factoryForModel(string $modelName)
	// {
	// 	return static::new();
	// }
}
