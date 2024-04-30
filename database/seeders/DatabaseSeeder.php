<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Base\Registry\Models\Registry;
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

		// create guest role
		$guestRole = \Base\ACL\Models\Role::factory()->create([
			'name' => 'GUEST',
			'description' => 'Guest role',
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

		// create permission for LAN
		\Base\ACL\Models\Permission::factory()->create([
			'name' => 'LAN',
			'description' => 'Local Area Network',
			'type' => 'IP',
			'content' => [
				'10.0.0.0/8',
				'172.16.0.0/12',
				'192.168.0.0/16',
			],
		]);

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
		\Base\User\Models\Profile::factory()->create(['user_id' => $user->id]);

		// attach admin role to admin user
		$user->roles()->attach($adminRole);

		// insert first time default general settings values
		Registry::insert([
			[
				'group' => 'themes',
				'keyword' => 'primary_color_scheme',
				'content' => 's:7:"#696969";',
			],
			[
				'group' => 'themes',
				'keyword' => 'danger_color_scheme',
				'content' => 's:7:"#e81717";',
			],
			[
				'group' => 'themes',
				'keyword' => 'gray_color_scheme',
				'content' => 's:7:"#292424";',
			],
			[
				'group' => 'themes',
				'keyword' => 'info_color_scheme',
				'content' => 's:7:"#a10da1";',
			],
			[
				'group' => 'themes',
				'keyword' => 'success_color_scheme',
				'content' => 's:7:"#0db30d";',
			],
			[
				'group' => 'themes',
				'keyword' => 'warning_color_scheme',
				'content' => 's:7:"#f0b32c";',
			],
			[
				'group' => 'themes',
				'keyword' => 'disable_top_navigation',
				'content' => 'b:0;',
			],
			[
				'group' => 'themes',
				'keyword' => 'revealable_passwords',
				'content' => 'b:1;',
			],
			[
				'group' => 'logins',
				'keyword' => 'default_register_roles',
				'content' => 'a:1:{i:0;s:5:"GUEST";}',
			],
			[
				'group' => 'logins',
				'keyword' => 'enable_register',
				'content' => 'b:1;',
			],
			[
				'group' => 'logins',
				'keyword' => 'enable_password_reset',
				'content' => 'b:1;',
			],
			[
				'group' => 'logins',
				'keyword' => 'enable_email_verification',
				'content' => 'b:1;',
			],
		]);

		Registry::query()->update([
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		]);
	}
}
