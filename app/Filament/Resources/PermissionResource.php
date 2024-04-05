<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Base\ACL\Facades\ACL;
use Filament\Resources\Resource;
use Base\ACL\Models\Permission;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SelectColumn;
use App\Filament\Resources\PermissionResource\Pages;

class PermissionResource extends Resource
{
	protected static ?string $model = Permission::class;

	protected static ?string $navigationIcon = 'heroicon-o-shield-check';

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
						Select::make('type')
							->required()
							->options(ACL::config()->allPermissionTypes)
							->native(false)
							->selectablePlaceholder(false)
							->label(__('Type'))
							->placeholder(__('Select permission type')),
						TagsInput::make('content')
							->reorderable()
							->label(__('Content'))
							->placeholder(__('Permission content')),
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
				SelectColumn::make('type')
					->label(__('Type'))
					->options(ACL::config()->allPermissionTypes)
					->selectablePlaceholder(false)
					->sortable()
					->searchable(),
				TagsColumn::make('content')
					->label(__('Content'))
					->sortable()
					->searchable(),
				TagsColumn::make('roles.name')
					->label(__('Roles'))
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
			'index' => Pages\ListPermissions::route('/'),
			// 'create' => Pages\CreatePermission::route('/create'),
			// 'edit' => Pages\EditPermission::route('/{record}/edit'),
		];
	}

	public static function getNavigationGroup(): string
	{
		return __('ACL');
	}

	public static function getNavigationLabel(): string
	{
		return __('Permissions');
	}
}
