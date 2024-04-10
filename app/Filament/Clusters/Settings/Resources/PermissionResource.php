<?php

namespace App\Filament\Clusters\Settings\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Base\ACL\Facades\ACL;
use Base\ACL\Models\Permission;
use Filament\Resources\Resource;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use App\Filament\Clusters\Settings\Resources\PermissionResource\Pages;

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
							->afterStateUpdated(fn($state, Forms\Set $set) => $set('name', preg_replace('/\s+/', '_', strtoupper((string) $state))))
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
