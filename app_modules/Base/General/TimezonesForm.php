<?php

namespace Base\General;

use Base\Timezone\Facades\Tz;
use Base\General\Facades\General;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class TimezonesForm
{
	public function get(): array
	{
		return [
			Section::make(__('Timezone'))
				->description(__('Select default timezone'))
				->aside()
				->schema([
					Group::make([
						TextInput::make('system_timezone')
							->label(__('System timezone'))
							->placeholder(Tz::getLabel(General::getSystemTimezone()))
							->readOnly()
							->dehydrated(),
						Select::make('timezone')
							->label(__('Default timezone'))
							->options(function () {
								return Tz::get();
							})
							->placeholder(General::getTimezone())
							->disablePlaceholderSelection(false)
							->searchable(true)
							->native(false)
					])->statePath('timezones')
				])->columns(2)
		];
	}
}