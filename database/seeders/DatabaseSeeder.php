<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

	private $role;

	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		// \App\Models\User::factory(10)->create();

		// \App\Models\User::factory()->create([
		//     'name' => 'Test User',
		//     'email' => 'test@example.com',
		// ]);

		$password = $this->command->ask("Enter password", 'password');

		$user = \App\Models\User::factory()->create([
			'name' => 'Admin',
			'username' => 'admin',
			'email' => 'admin@example.com',
			'password' => Hash::make($password),
		]);

		\App\Models\Profile::factory()->create(['user_id' => $user->id]);

		$permission = \Base\ACL\Models\Permission::factory()->create([
			'name' => 'ADMIN_DEFAULT_POLICY',
			'description' => 'Default permissions for Administrator',
			'type' => 'POLICY',
			'content' => [
				'user',
				'role',
				'permission',
				'token',
			]
		]);

		$role = \Base\ACL\Models\Role::factory()->create([
			'name' => 'ADMIN',
			'description' => 'Administrator role',
		]);

		$role->permissions()->attach($permission);
		$user->roles()->attach($role);
	}
}
