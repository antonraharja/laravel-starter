<?php

namespace Base\General;

use Base\Registry\Facades\Reg;
use Base\General\Traits\HasLogins;
use Base\General\Traits\HasThemes;
use Base\General\Traits\HasTimezones;

class General
{
	use HasThemes;
	use HasTimezones;
	use HasLogins;

	public function getGroup(string $group): array
	{
		$data = Reg::getGroup($group);

		$data = isset($data[$group]) ? $data[$group] : [];

		return $data;
	}

	public function getContent(string $group, string $keyword): null|string|array
	{
		return Reg::getContent($group, $keyword);
	}
}