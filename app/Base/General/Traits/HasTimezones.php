<?php

namespace Base\General\Traits;

use Base\Timezone\Facades\Tz;

trait HasTimezones
{
	private string $defaultTimezone = 'UTC';

	public function getTimezoneList(): array
	{
		return Tz::get();
	}

	public function getUserTimezone(): ?string
	{
		if ($timezone = auth()->user()->timezone) {
			if (Tz::getLabel($timezone)) {
				return $timezone;
			}
		}

		return null;
	}

	public function getAppTimezone(): string
	{
		if ($timezone = $this->getContent('timezones', 'timezone')) {
			if (Tz::getLabel($timezone)) {
				return $timezone;
			}
		}

		return $this->defaultTimezone;
	}

	public function getSystemTimezone(): ?string
	{
		if ($timezone = config('app.timezone')) {
			if (Tz::getLabel($timezone)) {
				return $timezone;
			}
		}

		return null;
	}

	public function getTimezone(): string
	{
		return $this->getUserTimezone() ?? $this->getAppTimezone();
	}
}