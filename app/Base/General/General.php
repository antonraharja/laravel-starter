<?php

namespace Base\General;

use Base\Timezone\Facades\Tz;
use Base\Registry\Facades\Reg;
use Filament\Support\Colors\Color;
use Illuminate\Support\Collection;

class General
{
	private string $defaultColorScheme = 'Zinc';

	private string $defaultTimezone = 'UTC';

	public function getGroup(string $group): Collection
	{
		$data = Reg::getGroup($group);

		$data = isset($data[$group]) ? $data[$group] : [];

		return collect($data);
	}

	public function getContent(string $group, string $keyword)
	{
		return Reg::getContent($group, $keyword);
	}

	public function getThemes(): Collection
	{
		return $this->getGroup('themes');
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

	public function getDefaultRegisterRoles(): array
	{
		return $this->getContent('users', 'default_register_roles');
	}

	public function getEnableRegister(): bool
	{
		return $this->getContent('users', 'enable_register');
	}

	public function getEnablePasswordReset(): bool
	{
		return $this->getContent('users', 'enable_password_reset');
	}

	public function getEnableEmailVerification(): bool
	{
		return $this->getContent('users', 'enable_email_verification');
	}
}