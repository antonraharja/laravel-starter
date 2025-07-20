<?php

namespace Base\Filament\Clusters\Settings\Resources\PermissionResource\Pages;

use Filament\Forms;
use Base\ACL\Helper;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Placeholder;
use Base\Filament\Clusters\Settings\Resources\PermissionResource;

class EditPermission extends EditRecord
{
	protected static string $resource = PermissionResource::class;

	protected function getHeaderActions(): array
	{
		return [
			// Actions\ViewAction::make(),
			Actions\DeleteAction::make(),
		];
	}

	public static function mutateFormData(array $data): array
	{
		if (isset($data['type'])) {
			unset($data['type']);
		}

		return $data;
	}

	public static function getEditForm(?string $permissionType): array
	{
		return [
			Section::make('Permission')
				->description(__('Define permission rules'))
				->aside()
				->schema(
					array_merge(
						[
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
							Placeholder::make('type')
								->content($permissionType)
								->label(__('Type')),
						],
						(new Helper)->getPermissionContentForm($permissionType)
					)
				)
		];
	}
}
