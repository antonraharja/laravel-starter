<?php

namespace App\Filament\Clusters\Settings\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Clusters\Settings\Resources\UserResource;

class ViewUser extends ViewRecord
{
	protected static string $resource = UserResource::class;

	protected function getHeaderActions(): array
	{
		return [
			Actions\EditAction::make(),
		];
	}
}
