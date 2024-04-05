<?php

namespace Base\General;

use Filament\Support\Colors\Color;
use Illuminate\Support\Collection;

class General extends Models\General
{
	private string $defaultColorScheme = 'Zinc';

	private string $defaultTimezone = 'UTC';

	public function getGroup(string $group): Collection
	{
		$returns = [];

		foreach ( $this->where(['group' => $group])->get(['keyword', 'content'])->toArray() as $row ) {
			$returns[$row['keyword']] = $row['content'];
		}

		return collect($returns);
	}

	public function getContent(string $group, string $keyword): ?string
	{
		return $this->where([
			'group' => $group,
			'keyword' => $keyword,
		])->get('content')->value('content');
	}

	public function getThemes(): Collection
	{
		return $this->getGroup('themes');
	}

	public function getSiteTitle(): ?string
	{
		return $this->getThemes()->get('site_title');
	}

	public function getBrandName(): ?string
	{
		return $this->getThemes()->get('brand_name');
	}

	public function getBrandLogo(): ?string
	{
		$brandLogo = $this->getThemes()->get('brand_logo');
		if ($brandLogo && file_exists(storage_path('app/' . $brandLogo))) {
			return asset('storage/' . $brandLogo);
		}

		return null;
	}

	public function getFavico(): ?string
	{
		$favico = $this->getThemes()->get('favico');
		if ($favico && file_exists(storage_path('app/' . $favico))) {
			return asset('storage/' . $favico);
		}

		return null;
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

	public function getTimezoneList(): array
	{
		return (new \Base\Timezone\Timezone)->get();
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