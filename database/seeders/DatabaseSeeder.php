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
		// create admin role
		$adminRole = \Base\ACL\Models\Role::factory()->create([
			'name' => 'ADMIN',
			'description' => 'Administrator role',
		]);

		// create user role
		$userRole = \Base\ACL\Models\Role::factory()->create([
			'name' => 'USER',
			'description' => 'User role',
		]);

		// create permission for admin and attach it to admin role
		$adminRole->permissions()->attach(
			\Base\ACL\Models\Permission::factory()->create([
				'name' => 'ADMIN_DEFAULT_BUNDLES',
				'description' => 'Default permissions for Administrator',
				'type' => 'BUNDLE',
				'content' => config('acl.bundles')
			])
		);

		// create permission for user and attach it to user role
		$userRole->permissions()->attach(
			\Base\ACL\Models\Permission::factory()->create([
				'name' => 'MANAGE_OWN_TOKEN',
				'description' => 'Default permissions for User',
				'type' => 'METHOD',
				'content' => [
					'token.view',
					'token.create',
					'token.delete',
				],
			])
		);

		// create another permission for admin and add it to admin role
		$adminRole->permissions()->attach(
			\Base\ACL\Models\Permission::factory()->create([
				'name' => 'CHANGE_USERNAME',
				'description' => 'Change username',
				'type' => 'TAG',
				'content' => [
					'change-username',
				],
			])
		);

		// create another permission for admin and add it to admin role
		$adminRole->permissions()->attach(
			\Base\ACL\Models\Permission::factory()->create([
				'name' => 'CHANGE_VERIFIED_AT',
				'description' => 'Change verified at',
				'type' => 'TAG',
				'content' => [
					'change-verified-at',
				],
			])
		);

		// ask for admin password
		$password = $this->command->ask("Enter admin password", 'password');

		// create default admin user with asked password
		$user = \App\Models\User::factory()->create([
			'name' => 'Admin',
			'username' => 'admin',
			'email' => 'admin@example.com',
			'password' => Hash::make($password),
		]);

		// create a profile for admin user
		\App\Models\Profile::factory()->create(['user_id' => $user->id]);

		// attach admin role to admin user
		$user->roles()->attach($adminRole);
	}
}
