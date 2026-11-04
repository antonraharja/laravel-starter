<?php

namespace Base\Filament\Clusters\Settings\Resources\UserResource\Pages;

use Filament\Actions\EditAction;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Actions;
use Base\General\Facades\General;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Base\Filament\Clusters\Settings\Resources\UserResource;

class ViewUser extends ViewRecord
{
	protected static string $resource = UserResource::class;

	protected function getHeaderActions(): array
	{
		return [
			EditAction::make(),
		];
	}

	public static function mutateFormData(array $data): array
	{
		return $data;
	}

	public static function getViewInfolist(): array
	{
		return [
			Flex::make([
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
							->disk('public')
							->circular(),
					])->grow(false)->hidden(fn($record): bool => empty ($record->profile->photo))
			])->from('md')
		];
	}
}
