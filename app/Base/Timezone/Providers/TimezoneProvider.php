<?php

namespace Base\Timezone\Providers;

use Base\Timezone\Timezone;
use Illuminate\Support\ServiceProvider;

class TimezoneProvider extends ServiceProvider
{
	/**
	 * Register services.
	 */
	public function register(): void
	{
		$this->app->bind('tz', function () {
			return new Timezone();
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
