<?php

namespace Base\General;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Base\General\Facades\General;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;

class ThemesForm
{
	public function get(): array
	{
		return [
			Section::make(__('Themes'))
				->description(__('Themes related settings'))
				->aside()
				->schema([
					Group::make([
						Flex::make([
							Grid::make(1)
								->schema([
									TextInput::make('brand_name')
										->label(__('Brand name'))
										->hint(__('Max. 30 chars'))
										->minLength(3)
										->maxLength(30),
									FileUpload::make('brand_logo')
										->label(__('Brand logo'))
										->previewable()
										->image()
										->disk('public')
										->directory('logo'),
									FileUpload::make('favico')
										->label(__('Favico'))
										->previewable()
										->image()
										->disk('public')
										->directory('favico'),
									Toggle::make('disable_top_navigation')
										->label(__('Disable top navigation')),
									Toggle::make('revealable_passwords')
										->label(__('Reveal passwords on password prompts')),
								]),
							Grid::make(1)
								->schema([
									ColorPicker::make('primary_color_scheme')
										->label(__('Primary color scheme'))
										->afterStateHydrated(fn(Set $set) => $set('primary_color_scheme', General::getPrimaryColorScheme())),
									ColorPicker::make('danger_color_scheme')
										->label(__('Danger color scheme'))
										->afterStateHydrated(fn(Set $set) => $set('danger_color_scheme', General::getDangerColorScheme())),
									ColorPicker::make('gray_color_scheme')
										->label(__('Gray color scheme'))
										->afterStateHydrated(fn(Set $set) => $set('gray_color_scheme', General::getGrayColorScheme())),
									ColorPicker::make('info_color_scheme')
										->label(__('Info color scheme'))
										->afterStateHydrated(fn(Set $set) => $set('info_color_scheme', General::getInfoColorScheme())),
									ColorPicker::make('success_color_scheme')
										->label(__('Success color scheme'))
										->afterStateHydrated(fn(Set $set) => $set('success_color_scheme', General::getSuccessColorScheme())),
									ColorPicker::make('warning_color_scheme')
										->label(__('Warning color scheme'))
										->afterStateHydrated(fn(Set $set) => $set('warning_color_scheme', General::getWarningColorScheme())),
								]),
						])
					])->statePath('themes'),
				]),
		];
	}
}