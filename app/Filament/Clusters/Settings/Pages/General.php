<?php

namespace App\Filament\Clusters\Settings\Pages;

use Exception;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Base\ACL\Facades\ACL;
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

class General extends Page
{
	protected static ?string $navigationIcon = 'heroicon-o-document-text';

	protected static string $view = 'filament.clusters.settings.pages.general';

	protected static ?string $cluster = Settings::class;

	public ?array $data = [];

	public static function canAccess(): bool
	{
		return auth()->user()->have('general.viewany general.edit');
	}

	public function mount(): void
	{
		$formData = [];

		foreach ( \Base\General\Models\General::all() as $item ) {
			$formData[$item->group][$item->keyword] = $item->content;
		}

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
												->required()
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
											Select::make('color_scheme')
												->label(__('Color scheme'))
												->options(function () {
													$colorOptions = [];
													foreach ( Color::all() as $scheme => $colorCodes ) {
														$colorOptions[$scheme] = ucwords(__($scheme));
													}
													return $colorOptions;
												})
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
												->placeholder((new \Base\Timezone\Timezone)->getLabel(\Base\General\Facades\General::getSystemTimezone()))
												->readOnly()
												->dehydrated(),
											Select::make('timezone')
												->label(__('Default timezone'))
												->options(function () {
													return (new \Base\Timezone\Timezone)->get();
												})
												->placeholder(\Base\General\Facades\General::getTimezone())
												->disablePlaceholderSelection(false)
												->searchable(true)
												->native(false)
										])->statePath('timezones')
									])->columns(2)
							]),
					]),
				Actions::make([
					Action::make('edit')
						->label(__('Save changes'))
						->submit('edit')
						->visible(ACL::have('general.edit')),
				])
			])->statePath('data');
	}

	public function edit(): void
	{
		try {
			$data = $this->form->getState();

			foreach ( $data as $group => $groupContent ) {
				foreach ( $groupContent as $keyword => $content ) {
					$group = trim($group);
					$keyword = trim($keyword);

					if ($group && $keyword) {
						\Base\General\Models\General::updateOrCreate([
							'group' => $group,
							'keyword' => $keyword,
						], [
							'content' => $content,
						]);
					}
				}
			}

			Notification::make()
				->title(__('Changes has been saved'))
				->status('success')
				->send();

		} catch (Exception $e) {
			Notification::make()
				->title($e->getMessage())
				->status('danger')
				->send();
		}

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
