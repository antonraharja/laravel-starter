<?php

namespace App\Filament\Clusters\Settings\Pages;

use Exception;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Base\ACL\Facades\ACL;
use Base\Timezone\Facades\Tz;
use Base\Registry\Facades\Reg;
use Base\General\Facades\General;
use Filament\Support\Colors\Color;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Actions\Action;

class GeneralSettings extends Page
{
	protected static ?string $navigationIcon = 'heroicon-o-document-text';

	protected static string $view = 'filament.clusters.settings.pages.general';

	protected static ?string $cluster = Settings::class;

	public ?array $data = [];

	public static function canAccess(): bool
	{
		return auth()->user()->have('general.viewany');
	}

	public function mount(): void
	{
		$formData = Reg::getAll();

		$this->form->fill($formData);
	}

	public function form(Form $form): Form
	{
		return $form
			->schema([
				Tabs::make('Tabs')
					->tabs([
						Tab::make(__('Themes'))
							->schema([
								Section::make(__('Themes'))
									->description(__('Themes related settings'))
									->aside()
									->schema([
										Group::make([
											TextInput::make('brand_name')
												->label(__('Brand name'))
												->hint(__('Max. 30 chars'))
												->minLength(3)
												->maxLength(30),
											FileUpload::make('brand_logo')
												->label(__('Brand logo'))
												->previewable()
												->image()
												->disk('local')
												->directory('logo'),
											FileUpload::make('favico')
												->label(__('Favico'))
												->previewable()
												->image()
												->disk('local')
												->directory('favico'),
											Select::make('primary_color_scheme')
												->label(__('Primary color scheme'))
												->options(General::getColorSelect()),
											Select::make('danger_color_scheme')
												->label(__('Danger color scheme'))
												->options(General::getColorSelect()),
											Select::make('gray_color_scheme')
												->label(__('Gray color scheme'))
												->options(General::getColorSelect()),
											Select::make('info_color_scheme')
												->label(__('Info color scheme'))
												->options(General::getColorSelect()),
											Select::make('success_color_scheme')
												->label(__('Success color scheme'))
												->options(General::getColorSelect()),
											Select::make('warning_color_scheme')
												->label(__('Warning color scheme'))
												->options(General::getColorSelect()),
											Toggle::make('disable_top_navigation')
												->label(__('Disable top navigation')),
											Toggle::make('revealable_passwords')
												->label(__('Reveal passwords on password prompts'))
										])->statePath('themes'),
									])->columns(2),
							]),
						Tab::make(__('Timezone'))
							->schema([
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
							]),
						Tab::make(__('Login'))
							->schema([
								Section::make(__('Login'))
									->description(__('Default settings for login'))
									->aside()
									->schema([
										Group::make([
											Select::make('default_register_roles')
												->label(__('Default register roles'))
												->multiple()
												->options(function () {
													return ACL::config()->allRolesSelect;
												})
												->preload()
												->native(false)
												->disabled(ACL::dontHave('role.viewany'))
												->hidden(ACL::dontHave('role.viewany')),
											Toggle::make('enable_register')
												->label(__('Enable register')),
											Toggle::make('enable_password_reset')
												->label(__('Enable password reset')),
											Toggle::make('enable_email_verification')
												->label(__('Enable email verification')),
										])->statePath('logins')
									])->columns(2)
							])
					]),
				Actions::make([
					Action::make('edit')
						->label(__('Save changes'))
						->submit('edit'),
				])
			])->statePath('data');
	}

	public function edit(): void
	{
		$error = null;

		if (ACL::dontHave('general.update')) {
			Notification::make()
				->title(__('Unauthorized'))
				->status('danger')
				->send();

			return;
		}

		try {
			if ($data = $this->form->getState()) {
				Reg::save($data);

				if (Reg::saved()) {

					Notification::make()
						->title(__('Changes has been saved'))
						->status('success')
						->send();

					return;
				}
			}

			$savedData = Reg::savedData();

			if (is_string($savedData)) {
				$error = $savedData;
			} else {
				$error = __('No data found');
			}
		} catch (Exception $e) {
			$error = $e->getMessage();
		}

		Notification::make()
			->title($error)
			->status('danger')
			->send();

		return;
	}

	public static function getNavigationGroup(): string
	{
		return __('System');
	}

	public static function getNavigationLabel(): string
	{
		return __('General Settings');
	}

	public static function getLabel(): string
	{
		return __('General Settings');
	}

	public static function getModelLabel(): string
	{
		return __('General Settings');
	}

	public static function getPluralLabel(): string
	{
		return __('General Settings');
	}

	public static function getPluralModelLabel(): string
	{
		return __('General Settings');
	}

	public function getTitle(): string|Htmlable
	{
		return __('General Settings');
	}
}
