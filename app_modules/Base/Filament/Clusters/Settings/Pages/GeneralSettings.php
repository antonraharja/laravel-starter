<?php

namespace Base\Filament\Clusters\Settings\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Exception;
use Filament\Pages\Page;
use Base\ACL\Facades\ACL;
use Base\Registry\Facades\Reg;
use Base\General\LoginsForm;
use Base\General\ThemesForm;
use Base\General\TimezonesForm;
use Base\Filament\Clusters\Settings;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;

class GeneralSettings extends Page
{
	protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

	protected string $view = 'filament.clusters.settings.pages.general';

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

	public function form(Schema $schema): Schema
	{
		return $schema
			->components([
				Tabs::make('Tabs')
					->tabs([
						Tab::make(__('Themes'))
							->schema((new ThemesForm)->get()),
						Tab::make(__('Timezone'))
							->schema((new TimezonesForm)->get()),
						Tab::make(__('Login'))
							->schema((new LoginsForm)->get())
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
