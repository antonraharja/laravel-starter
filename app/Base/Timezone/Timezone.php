<?php

namespace Base\Timezone;

use DateTimeZone;

class Timezone
{
	private string $utcLabel = 'UTC';

	private array $timezones = [];

	private array $timezoneSelects = [];

	public function __construct(bool $getFromConfig = false)
	{
		if ($getFromConfig) {
			$this->config();
		} else {
			$this->listAbbreviations();

			$this->listSelectOptions();
		}
	}

	public function get(): array
	{
		return $this->timezones;
	}

	public function select(): array
	{
		return $this->timezoneSelects;
	}

	public function config(): Timezone
	{
		$this->timezones = config('timezone.timezones');

		$this->timezoneSelects = config('timezone.timezoneSelects');

		return $this;
	}

	protected function listAbbreviations(): Timezone
	{
		$this->timezones = [];

		foreach ( DateTimeZone::listAbbreviations() as $abbr ) {
			foreach ( $abbr as $key => $zone ) {
				if ($zone['timezone_id']) {
					$this->timezones[$zone['timezone_id']] = round($zone['offset'] / 3600);
				}
			}
		}

		array_multisort($this->timezones);

		return $this;
	}

	protected function listSelectOptions(): Timezone
	{
		foreach ( $this->timezones as $zone => $offset ) {
			if ($offset > 0) {
				$offset = '+' . sprintf("%02d", $offset) . ':00';
			} else if ($offset < 0) {
				$offset = '-' . sprintf("%02d", str_replace('-', '', $offset)) . ':00';
			} else {
				$offset = '+00:00';
			}

			$this->timezoneSelects[$zone] = '(' . $this->utcLabel . $offset . ') ' . $zone;
		}

		return $this;
	}
}

