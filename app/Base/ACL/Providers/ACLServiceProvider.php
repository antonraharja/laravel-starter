<?php

namespace Base\ACL\Providers;

use Base\ACL\Services\ACLServices;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class ACLServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 */
	public function register(): void
	{
		$this->app->bind(ACLServices::class, function (Application $app) {
			return new ACLServices();
		});

		$this->app->bind('acl', function () {
			return new ACLServices();
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
