<?php

namespace App\Filament\Clusters\Settings\Resources;

use App\Models\User;
use Filament\Resources\Resource;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Settings\Resources\UserResource\Pages;

class UserResource extends Resource
{
	protected static ?string $model = User::class;

	protected static ?string $navigationIcon = 'heroicon-o-user';

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
			'index' => Pages\ListUsers::route('/'),
			// 'view' => Pages\ViewUser::route('/{record}')
			// 'create' => Pages\CreateUser::route('/create'),
			// 'edit' => Pages\EditUser::route('/{record}/edit'),
		];
	}

	public static function getNavigationGroup(): string
	{
		return __('System');
	}

	public static function getNavigationLabel(): string
	{
		return __('Users');
	}
}
