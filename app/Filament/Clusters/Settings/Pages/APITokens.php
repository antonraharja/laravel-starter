<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Base\ACL\Facades\ACL;
use Filament\Tables\Table;
use Base\Token\Models\Token;
use Base\Token\Traits\HasToken;
use Base\General\Facades\General;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\Action as ActionColumn;

class APITokens extends Page implements HasForms, HasTable
{
	use InteractsWithForms, InteractsWithTable, HasToken;

	protected static ?string $cluster = Settings::class;

	public ?array $data = [];

	protected static ?string $slug = 'api-tokens';

	protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

	protected static string $view = 'filament.pages.apitoken';

	public static function canAccess(): bool
	{
		return auth()->user()->have('token.viewany token.create');
	}

	public function mount(): void
	{
		$this->form->fill();
	}

	public function form(Form $form): Form
	{
		return $form
			->schema([
				Section::make(__('API Token'))
					->description(__('Create new API token'))
					->schema([
						Grid::make()
							->schema([
								TextInput::make('name')
									->label(__('Token label'))
									->hint(__('Max. 30 chars'))
									->required()
									->minLength(3)
									->maxLength(30),
								Select::make('tokenable_id')
									->label(__('Token owner'))
									->placeholder(__('Select a user'))
									->options(function () {
										$returns = [];
										$q = ACL::have('token.viewany')
											? User::with('profile')->get()
											: User::with('profile')->where('id', auth()->user()->id)->get();
										foreach ( $q as $user ) {
											$returns[$user->id] = auth()->user()->getFilamentName();
										}
										$returns = array_unique($returns);
										return $returns;
									})
									->searchable()
									->preload()
									->native(false)
									->visible(ACL::have('token.viewany')),
								DateTimePicker::make('expires_at')
									->label(__('Expire'))
									->hint(__('Default 60 days'))
									->minDate(now())
									->afterOrEqual('now'),
							])->columns(3),
						Actions::make([
							Action::make('create')
								->label(__('Create new token'))
								->submit('create')
								->visible(ACL::have('token.create')),
						])
					])->columns(3),
			])->statePath('data');
	}

	public function create(): void
	{
		list($error, $message, $token) = $this->createToken($this->form)->getToken();

		switch ($error) {
			case 200:
				Notification::make()
					->title($message)
					->body($token)
					->status('success')
					->persistent()
					->send();

				$this->form->fill([]);

				return;
			case 400:
			case 401:
			case 500:
				Notification::make()
					->title($message)
					->status('danger')
					->send();
				return;
		}

		Notification::make()
			->title(__('Unknown error'))
			->status('danger')
			->send();

		return;
	}

	public function table(Table $table): Table
	{
		$profile = auth()->user()->profile;

		return $table
			->modelLabel(__('API Token'))
			->query(function () {
				if (ACL::have('token.viewany')) {
					return Token::query();
				} else {
					return Token::query()->where('tokenable_id', auth()->user()->id);
				}
			})
			->defaultSort('expires_at', 'desc')
			->columns([
				TextColumn::make('id')
					->label(__('ID'))
					->sortable()
					->searchable(),
				TextColumn::make('name')
					->label(__('Label'))
					->sortable()
					->searchable(),
				TextColumn::make('tokenable_id')
					->label(__('Token owner'))
					->searchable()
					->sortable()
					->formatStateUsing(function ($state) {
						$user = User::find($state);
						return $user->getFilamentName();
					})
					->visible(ACL::have('token.viewany')),
				TextColumn::make('expires_at')
					->label(__('Expire'))
					->dateTime()
					->timezone(General::getTimezone())
					->sortable()
					->searchable(),
				TextColumn::make('last_used_at')
					->label(__('Last use'))
					->dateTime()
					->timezone(General::getTimezone())
					->sortable()
					->searchable(),
				TextColumn::make('created_at')
					->label(__('Created'))
					->dateTime()
					->timezone(General::getTimezone())
					->sortable()
					->searchable()
					->toggleable(isToggledHiddenByDefault: true),
			])
			->filters([
				//
			])
			->actions([
				DeleteAction::make('delete')
					->label('')
					->tooltip(__('Delete'))
					->visible(ACL::have('token.delete'))
					->before(function ($record, ActionColumn $action) {
						if (ACL::have('token.delete')) {
							if ($record->delete()) {
								Notification::make()
									->title(__('Token has been deleted'))
									->status('success')
									->send();
							} else {
								Notification::make()
									->title(__('Fail to delete token'))
									->status('danger')
									->send();
							}
						} else {
							Notification::make()
								->title(__('Unauthorized'))
								->status('danger')
								->send();

							$action->cancel();
						}
					}),
				// Tables\Actions\DeleteAction::make(),
				// Tables\Actions\ViewAction::make(),
				EditAction::make('edit')
					->label('')
					->tooltip(__('Edit'))
					->visible(ACL::have('token.edit'))
					->form([
						Section::make()
							->description(__('Update API token name and expire date/time'))
							->aside()
							->schema([
								TextInput::make('name')
									->label(__('Token label'))
									->hint(__('Max. 30 chars'))
									->required()
									->minLength(3)
									->maxLength(30),
								DateTimePicker::make('expires_at')
									->label(__('Expire'))
									->hint(__('Default 60 days'))
									->minDate(now())
									->afterOrEqual('now'),
							])
					]),
			])
			->bulkActions([
				// Tables\Actions\BulkActionGroup::make([
				// 	Tables\Actions\DeleteBulkAction::make(),
				// ]),
			]);
	}

	public static function getNavigationGroup(): string
	{
		return __('System');
	}

	public static function getNavigationLabel(): string
	{
		return __('API Tokens');
	}

	public static function getLabel(): string
	{
		return __('API Tokens');
	}

	public static function getModelLabel(): string
	{
		return __('API Tokens');
	}

	public static function getPluralLabel(): string
	{
		return __('API Tokens');
	}

	public static function getPluralModelLabel(): string
	{
		return __('API Tokens');
	}

	public function getTitle(): string|Htmlable
	{
		return __('API Tokens');
	}
}
