<?php

namespace Base\Filament\Clusters\Settings\Resources\PermissionResource\Pages;

use Filament\Forms;
use Base\ACL\Helper;
use Base\ACL\Facades\ACL;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Base\Filament\Clusters\Settings\Resources\PermissionResource;

class CreatePermission extends CreateRecord
{
	protected static string $resource = PermissionResource::class;

	public static function mutateFormData(array $data): array
	{
		$data['content'] = [];

		return $data;
	}

	public static function getCreateForm(): array
	{
		return [
			Section::make('Permission')
				->description(__('Define permission rules'))
				->aside()
				->schema(
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
						Select::make('type')
							->required()
							->options(ACL::config()->allPermissionTypes)
					]
				)
		];
	}
}
