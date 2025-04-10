<?php

namespace Base\Starter\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class StarterServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 */
	public function register(): void
	{
		//
	}

	/**
	 * Bootstrap services.
	 */
	public function boot(): void
	{
		// Starter Facades
		$aliases = [
			// ACL
			'ACL' => \Base\ACL\Facades\ACL::class,

			// Timezone
			'Tz' => \Base\Timezone\Facades\Tz::class,

			// Registry
			'Reg' => \Base\Registry\Facades\Reg::class,
		];

		$loader = AliasLoader::getInstance();
		foreach ( $aliases as $alias => $aliasClass ) {
			$loader->alias($alias, $aliasClass);
		}
		// End of Starter Facades

		// Base\ACL
		Blade::if('have', function (string|array $permissions) {
			return auth()->user()->have($permissions);
		});

		Blade::if('dontHave', function (string|array $permissions) {
			return auth()->user()->dontHave($permissions);
		});

		Blade::if('role', function (string|array $role) {
			return auth()->user()->role($role);
		});

		Blade::if('isAdmin', function () {
			return auth()->user()->isAdmin();
		});
		// End of Base\ACL
	}
}
