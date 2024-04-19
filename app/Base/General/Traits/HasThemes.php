<?php

namespace Base\General\Traits;

use Base\General\Facades\General;
use Filament\Support\Colors\Color;
use Illuminate\Support\Collection;

trait HasThemes
{
	public string $defaultPrimaryColorScheme = 'Zinc';

	public string $defaultDangerColorScheme = 'Red';

	public string $defaultGrayColorScheme = 'Gray';

	public string $defaultInfoColorScheme = 'Indigo';

	public string $defaultSuccessColorScheme = 'Green';

	public string $defaultWarningColorScheme = 'Amber';

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

	public function getPrimaryColorScheme()
	{
		$method = !empty(General::getContent('themes', 'primary_color_scheme'))
			? ucwords(General::getContent('themes', 'primary_color_scheme'))
			: $this->defaultPrimaryColorScheme;
		$class = Color::class;
		$const = "$class::$method";

		return constant($const);
	}

	public function getDangerColorScheme()
	{
		$method = !empty(General::getContent('themes', 'danger_color_scheme'))
			? ucwords(General::getContent('themes', 'danger_color_scheme'))
			: $this->defaultDangerColorScheme;
		$class = Color::class;
		$const = "$class::$method";

		return constant($const);
	}

	public function getGrayColorScheme()
	{
		$method = !empty(General::getContent('themes', 'gray_color_scheme'))
			? ucwords(General::getContent('themes', 'gray_color_scheme'))
			: $this->defaultGrayColorScheme;
		$class = Color::class;
		$const = "$class::$method";

		return constant($const);
	}

	public function getInfoColorScheme()
	{
		$method = !empty(General::getContent('themes', 'info_color_scheme'))
			? ucwords(General::getContent('themes', 'info_color_scheme'))
			: $this->defaultInfoColorScheme;
		$class = Color::class;
		$const = "$class::$method";

		return constant($const);
	}

	public function getSuccessColorScheme()
	{
		$method = !empty(General::getContent('themes', 'success_color_scheme'))
			? ucwords(General::getContent('themes', 'success_color_scheme'))
			: $this->defaultSuccessColorScheme;
		$class = Color::class;
		$const = "$class::$method";

		return constant($const);
	}

	public function getWarningColorScheme()
	{
		$method = !empty(General::getContent('themes', 'warning_color_scheme'))
			? ucwords(General::getContent('themes', 'warning_color_scheme'))
			: $this->defaultPrimaryColorScheme;
		$class = Color::class;
		$const = "$class::$method";

		return constant($const);
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