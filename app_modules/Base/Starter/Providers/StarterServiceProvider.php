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
		$this->mergeConfigFrom(
            __DIR__.'/../../config/acl.php', 'modules.base.acl'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../../config/timezone.php', 'modules.base.timezone'
        );
	}

	/**
	 * Bootstrap services.
	 */
	public function boot(): void
	{
        $this->publishes([
            __DIR__.'/../../config/acl.php' => config_path('modules/base/acl.php'),
            __DIR__.'/../../config/timezone.php' => config_path('modules/base/timezone.php'),
        ], 'config');

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