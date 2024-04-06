<?php

namespace App\Filament\Clusters\Settings\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Base\ACL\Models\Role;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Clusters\Settings\Resources\RoleResource\Pages;

class RoleResource extends Resource
{
	protected static ?string $model = Role::class;

	protected static ?string $navigationIcon = 'heroicon-o-credit-card';

	protected static ?string $cluster = Settings::class;

	public static function form(Form $form): Form
	{
		return $form
			->schema([
				Section::make('Permission')
					->description(__('Define permission rules'))
					->aside()
					->schema([
						TextInput::make('name')
							->required()
							->unique(ignoreRecord: true)
							->alphaDash()
							->minLength(3)
							->maxLength(30)
							->lazy()
							->afterStateUpdated(fn($state, Forms\Set $set) => $set('name', str_replace(' ', '_', strtoupper((string) $state))))
							->hint(__('Max. 30 chars')),
						TextInput::make('description'),
						Select::make('permissions.name')
							->label(__('Select permissions'))
							->multiple()
							->relationship('permissions', 'name')
							->searchable(['name'])
							->preload()
							->native(false),
					])
			]);
	}

	public static function table(Table $table): Table
	{
		return $table
			->reorderable('order_column')
			->defaultSort('order_column')
			->columns([
				TextColumn::make('name')
					->sortable()
					->searchable(),
				TextColumn::make('description')
					->sortable()
					->searchable()
					->toggleable(isToggledHiddenByDefault: true),
				TagsColumn::make('permissions.name')
					->sortable()
					->searchable(),
				TextColumn::make('created_at')
					->label(__('Created'))
					->dateTime()
					->timezone(\Base\General\Facades\General::getTimezone())
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
				TextColumn::make('updated_at')
					->label(__('Updated'))
					->dateTime()
					->timezone(\Base\General\Facades\General::getTimezone())
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
			])
			->filters([
				//
			])
			->actions([
				Tables\Actions\DeleteAction::make()
					->label('')
					->tooltip(_('Delete')),
				Tables\Actions\EditAction::make()
					->label('')
					->tooltip(__('Edit')),
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make(),
				]),
			]);
	}

	public static function getRelations(): array
	{
		return [
			//
		];
	}

	public static function getPages(): array
	{
		return [
			'index' => Pages\ListRoles::route('/'),
			// 'create' => Pages\CreateRole::route('/create'),
			// 'edit' => Pages\EditRole::route('/{record}/edit'),
		];
	}

	public static function getNavigationGroup(): string
	{
		return __('ACL');
	}

	public static function getNavigationLabel(): string
	{
		return __('Roles');
	}
}
