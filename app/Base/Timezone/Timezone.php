<?php

namespace Base\Timezone;

class Timezone
{
	private array $timezones = [];

	public function __construct()
	{
		$this->timezones = config('timezone.timezones');
	}

	public function get(): array
	{
		return is_array($this->timezones) ? $this->timezones : [];
	}

	public function getLabel(string $zone): string
	{
		return isset($this->timezones[$zone]) ? $this->timezones[$zone] : '';
	}
}

