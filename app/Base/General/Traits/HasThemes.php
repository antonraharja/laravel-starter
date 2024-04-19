<?php

namespace Base\General\Traits;

use Base\General\Facades\General;
use Filament\Support\Colors\Color;
use Illuminate\Support\Collection;

trait HasThemes
{
	public string $defaultPrimaryColorScheme = '#696969';	// onyx

	public string $defaultDangerColorScheme = '#e81717';	// red

	public string $defaultGrayColorScheme = '#a6a6a6';		// gray

	public string $defaultInfoColorScheme = '#a10da1';		// indigo

	public string $defaultSuccessColorScheme = '#0db30d';	// green

	public string $defaultWarningColorScheme = '#f0b32c';	// orange

	public function getThemes(): Collection
	{
		$themes = General::getGroup('themes');

		return isset($themes) && is_array($themes) ? collect($themes) : collect([]);
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

	public function getColorSelect(): array
	{
		$colorOptions = [];

		foreach ( Color::all() as $scheme => $colorCodes ) {
			$colorOptions[$scheme] = ucwords(__($scheme));
		}

		return $colorOptions;
	}

	public function getColorScheme(string $scheme): string
	{
		$defaultScheme = match ($scheme) {
			'primary_color_scheme' => $this->defaultPrimaryColorScheme,
			'danger_color_scheme' => $this->defaultDangerColorScheme,
			'gray_color_scheme' => $this->defaultGrayColorScheme,
			'info_color_scheme' => $this->defaultInfoColorScheme,
			'success_color_scheme' => $this->defaultSuccessColorScheme,
			'warning_color_scheme' => $this->defaultWarningColorScheme,
		};

		if ($color = strtolower(trim(General::getContent('themes', $scheme)))) {

			return $color;
		}

		return $defaultScheme;
	}

	public function getPrimaryColorScheme(): string
	{
		return $this->getColorScheme('primary_color_scheme');
	}

	public function getDangerColorScheme(): string
	{
		return $this->getColorScheme('danger_color_scheme');
	}

	public function getGrayColorScheme(): string
	{
		return $this->getColorScheme('gray_color_scheme');
	}

	public function getInfoColorScheme(): string
	{
		return $this->getColorScheme('info_color_scheme');
	}

	public function getSuccessColorScheme(): string
	{
		return $this->getColorScheme('success_color_scheme');
	}

	public function getWarningColorScheme(): string
	{
		return $this->getColorScheme('warning_color_scheme');
	}

	public function getDisableTopNavigation(): bool
	{
		$disableTopNavigation = General::getContent('themes', 'disable_top_navigation');

		return $disableTopNavigation ?? false;
	}

	public function getRevealablePasswords(): bool
	{
		$reveablePasswords = General::getContent('themes', 'revealable_passwords');

		return $reveablePasswords ?? false;
	}
}