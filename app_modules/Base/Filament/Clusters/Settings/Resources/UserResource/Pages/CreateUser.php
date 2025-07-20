<?php

namespace Base\Filament\Clusters\Settings\Resources\UserResource\Pages;

use Base\ACL\Facades\ACL;
use Base\Timezone\Facades\Tz;
use Illuminate\Support\Carbon;
use Base\General\Facades\General;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\DateTimePicker;
use Base\Filament\Clusters\Settings\Resources\UserResource;

class CreateUser extends CreateRecord
{
	protected static string $resource = UserResource::class;

	public static function mutateFormData(array $data): array
	{
		if (ACL::dontHave(aclhc('change-username')) && isset($data['username'])) {
			unset($data['username']);
		}

		if (ACL::have(aclhc('change-verified-at'))) {
			if (!isset($data['email_verified_at'])) {
				$data['email_verified_at'] = Carbon::now()->timezone(General::getTimezone());
			}
		} else {
			if (isset($data['email_verified_at'])) {
				unset($data['email_verified_at']);
			}
		}

		return $data;
	}

	public static function getCreateForm(): array
	{
		return [
			Section::make(__('Account'))
				->description(__('Account information used for login'))
				->aside()
				->schema([
					TextInput::make('username')
						->required()
						->unique(ignoreRecord: true)
						->alphaNum()
						->minLength(3)
						->maxLength(20)
						->disabled(ACL::dontHave(aclhc('change-username'))),
					TextInput::make('email')
						->required()
						->unique(ignoreRecord: true)
						->email(),
					DateTimePicker::make('email_verified_at')
						->label(__('Verified'))
						->native(false)
						->maxDate(now()->timezone(General::getTimezone()))
						->timezone(General::getTimezone())
						->disabled(ACL::dontHave(aclhc('change-verified-at'))),
				]),
			Section::make(__('Roles'))
				->description(__('Select roles for this account'))
				->aside()
				->disabled(ACL::dontHave('role.viewany'))
				->hidden(ACL::dontHave('role.viewany'))
				->schema([
					Select::make('roles')
						->multiple()
						->required()
						->relationship(name: 'roles', titleAttribute: 'name')
						->searchable(['name'])
						->preload()
						->native(false)
						->disabled(ACL::dontHave('role.viewany'))
						->hidden(ACL::dontHave('role.viewany')),
				]),
			Section::make(__('Password'))
				->description(__('Change password with a new secure password'))
				->aside()
				->schema([
					TextInput::make('password')
						->label(__('New password'))
						->required(fn(string $operation): bool => $operation === 'create')
						->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
						->dehydrated(fn(?string $state): bool => filled($state))
						->confirmed()
						->minLength(12)
						->password(),
					TextInput::make('password_confirmation')
						->label(__('Confirm new password'))
						->same('password')
						->requiredWith('password')
						->password()
				]),
			Section::make(__('Personal'))
				->description(__('Personal information'))
				->aside()
				->relationship(name: 'profile')
				->schema([
					TextInput::make('first_name')
						->label(__('First name'))
						->required(),
					TextInput::make('last_name')
						->label(__('Last name')),
					TextInput::make('contact')
						->label(__('Phone number'))
						->tel(),
					FileUpload::make('photo')
						->label(__('Profile picture'))
						->disk('local')
						->directory('avatars')
						->avatar()
						->image(),
					DatePicker::make('dob')
						->label(__('Date of birth'))
						->native(false),
					TextInput::make('country')
						->label(__('Country'))
						->maxLength(100),
					TextInput::make('city')
						->label(__('City'))
						->maxLength(100),
					Textarea::make('address')
						->label(__('Address'))
						->maxLength(255),
					Textarea::make('bio')
						->label(__('Bio'))
						->maxLength(255),
				]),
			Section::make(__('Settings'))
				->description(__('Account settings available for this account'))
				->aside()
				->schema([
					Select::make('timezone')
						->label(__('Timezone'))
						->options(function () {
							return Tz::get();
						})
						->placeholder(General::getTimezone())
						->disablePlaceholderSelection(false)
						->preload()
						->searchable(true)
						->native(false)
				])
		];
	}
}
