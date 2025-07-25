<?php

namespace Base\Filament\Clusters\Settings\Resources\UserResource\Pages;

use Filament\Actions;
use Base\General\Facades\General;
use Filament\Infolists\Components\Split;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Base\Filament\Clusters\Settings\Resources\UserResource;

class ViewUser extends ViewRecord
{
	protected static string $resource = UserResource::class;

	protected function getHeaderActions(): array
	{
		return [
			Actions\EditAction::make(),
		];
	}

	public static function mutateFormData(array $data): array
	{
		return $data;
	}

	public static function getViewInfolist(): array
	{
		return [
			Split::make([
				Section::make(__('Account'))
					->schema([
						TextEntry::make('username'),
						TextEntry::make('email'),
						TextEntry::make('email_verified_at')
							->label(__('Verified')),
						TextEntry::make('roles.name')
							->label(__('Roles'))
							->listWithLineBreaks(),
						TextEntry::make('timezone')
							->default(General::getTimezone()),
					]),
				Section::make(__('Personal'))
					->schema([
						TextEntry::make('profile.first_name')
							->label(__('First name')),
						TextEntry::make('profile.last_name')
							->label(__('Last name')),
						TextEntry::make('profile.contact')
							->label(__('Phone number')),
						TextEntry::make('profile.dob')
							->label(__('Date of birth')),
						TextEntry::make('profile.country')
							->label(__('Country')),
						TextEntry::make('profile.city')
							->label(__('City')),
						TextEntry::make('profile.address')
							->label(__('Address')),
						TextEntry::make('profile.bio')
							->label(__('Bio')),
					]),
				Section::make('')
					->schema([
						ImageEntry::make('profile.photo')
							->label('')
							->disk('local')
							->circular(),
					])->grow(false)->hidden(fn($record): bool => empty ($record->profile->photo))
			])->from('md')
		];
	}
}
