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
		$method = ucwords($this->getContent('themes', 'color_scheme')) ?? $this->defaultColorScheme;
		$class = '\Filament\Support\Colors\Color';
		$const = "$class::$method";

		return constant($const);
	}

	public function getTimezone()
	{
		return $this->getContent('timezones', 'timezone') ?? $this->defaultTimezone;
	}
}