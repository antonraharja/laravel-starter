<?php

namespace App\Filament\Clusters\Settings\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Base\ACL\Models\Role;
use Filament\Resources\Resource;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
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
				Section::make('Role')
					->description(__('Select permissions belongs to the role'))
					->aside()
					->schema([
						TextInput::make('name')
							->required()
							->unique(ignoreRecord: true)
							->alphaDash()
							->minLength(3)
							->maxLength(30)
							->lazy()
							->afterStateUpdated(fn($state, Forms\Set $set) => $set('name', preg_replace('/\s+/', '_', strtoupper((string) $state))))
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
		return __('User Managements');
	}

	public static function getNavigationLabel(): string
	{
		return __('Roles');
	}
}
