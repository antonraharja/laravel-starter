<?php

namespace Base\Registry\Providers;

use Base\Registry\SimpleRegistry;
use Illuminate\Support\ServiceProvider;

class RegistryProvider extends ServiceProvider
{
	/**
	 * Register services.
	 */
	public function register(): void
	{
		$this->app->bind('reg', function () {
			return new SimpleRegistry();
		});
	}

	/**
	 * Bootstrap services.
	 */
	public function boot(): void
	{
		//
	}
}
