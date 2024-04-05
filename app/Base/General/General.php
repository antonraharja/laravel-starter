<?php

namespace Base\General;

use Filament\Support\Colors\Color;

class General extends Models\General
{
	private string $defaultColorScheme = 'Zinc';

	private string $defaultTimezone = 'UTC';

	public function getContent(string $group, string $keyword): ?string
	{
		return $this->where([
			'group' => $group,
			'keyword' => $keyword,
		])->get('content')->value('content');
	}

	public function getColor()
	{
		$method = !empty($this->getContent('themes', 'color_scheme'))
			? ucwords($this->getContent('themes', 'color_scheme'))
			: $this->defaultColorScheme;
		$class = Color::class;
		$const = "$class::$method";

		return constant($const);
	}

	public function getUserTimezone(): ?string
	{
		if ($timezone = auth()->user()->timezone) {
			if ((new \Base\Timezone\Timezone)->getLabel($timezone)) {
				return $timezone;
			}
		}

		return null;
	}

	public function getAppTimezone(): string
	{
		if ($timezone = $this->getContent('timezones', 'timezone')) {
			if ((new \Base\Timezone\Timezone)->getLabel($timezone)) {
				return $timezone;
			}
		}

		return $this->defaultTimezone;
	}

	public function getSystemTimezone(): ?string
	{
		if ($timezone = config('app.timezone')) {
			if ((new \Base\Timezone\Timezone)->getLabel($timezone)) {
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