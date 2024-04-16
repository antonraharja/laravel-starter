<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
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
			return auth()->user()->role(aclhc('ADMIN'));
		});
	}
}
