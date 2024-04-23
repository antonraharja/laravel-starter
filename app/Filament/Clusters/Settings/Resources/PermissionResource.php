<?php

namespace App\Filament\Clusters\Settings\Resources;

use Base\ACL\Models\Permission;
use Filament\Resources\Resource;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Settings\Resources\PermissionResource\Pages;

class PermissionResource extends Resource
{
	protected static ?string $model = Permission::class;

	protected static ?string $navigationIcon = 'heroicon-o-shield-check';

	protected static ?string $cluster = Settings::class;

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
		return __('User Managements');
	}

	public static function getNavigationLabel(): string
	{
		return __('Permissions');
	}
}
