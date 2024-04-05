<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms\Form;
use Base\ACL\Facades\ACL;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\UserResource\Pages;
use Filament\Infolists\Components\Section as SectionList;

class UserResource extends Resource
{
	protected static ?string $model = User::class;

	protected static ?string $navigationIcon = 'heroicon-o-user';

	protected static ?string $cluster = Settings::class;

	public static function form(Form $form): Form
	{
		return $form
			->schema([
				Tabs::make('Tabs')
					->tabs([
						Tab::make('Account')
							->schema([
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
											->disabled(fn(string $operation): bool => !ACL::role('ADMIN'))
											->dehydrated(fn(string $operation): bool => !ACL::role('ADMIN')),
										TextInput::make('email')
											->required()
											->unique(ignoreRecord: true)
											->email(),
										DateTimePicker::make('email_verified_at')
											->label(__('Verified'))
											->native(false)
											->maxDate(now()->timezone(\Base\General\Facades\General::getTimezone()))
											->timezone(\Base\General\Facades\General::getTimezone())
											->disabled(fn(string $operation): bool => !ACL::role('ADMIN'))
											->dehydrated(fn(string $operation): bool => !ACL::role('ADMIN'))
									]),
								Section::make(__('Roles'))
									->description(__('Select roles for this account'))
									->aside()
									->disabled(fn(string $operation): bool => !ACL::role('ADMIN'))
									->hidden(fn(string $operation): bool => !ACL::role('ADMIN'))
									->schema([
										Select::make('roles')
											->multiple()
											->required()
											->relationship(name: 'roles', titleAttribute: 'name')
											->searchable(['name'])
											->preload()
											->native(false)
											->disabled(fn(string $operation): bool => !ACL::role('ADMIN'))
											->dehydrated(fn(string $operation): bool => !ACL::role('ADMIN'))
											->hidden(fn(string $operation): bool => !ACL::role('ADMIN')),
									])
							]),
						Tab::make('Password')
							->schema([
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
									])
							])->visible(fn(string $operation): bool => $operation !== 'view'),
						Tab::make('Personal')
							->schema([
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
									])
							]),
						Tab::make('Settings')
							->schema([
								Section::make(__('Settings'))
									->description(__('Account settings available for this account'))
									->aside()
									->schema([
										Select::make('timezone')
											->label(__('Timezone'))
											->options(function () {
												return (new \Base\Timezone\Timezone)->get();
											})
											->placeholder(\Base\General\Facades\General::getTimezone())
											->disablePlaceholderSelection(false)
											->preload()
											->searchable(true)
											->native(false)
									])
							]),
					])
			])->columns(1);
	}

	public static function infolist(Infolist $infolist): Infolist
	{
		return $infolist
			->schema([
				SectionList::make(__('Account'))
					->description(__('Account information used for login'))
					->aside()
					->schema([
						TextEntry::make('username'),
						TextEntry::make('email'),
						TextEntry::make('email_verified_at')
							->label(__('Verified'))
					]),
				SectionList::make(__('Role'))
					->description(__('Selected roles for this account'))
					->aside()
					->schema([
						TextEntry::make('roles.name')
							->label(__('Roles'))
							->listWithLineBreaks(),
					]),
				SectionList::make(__('Personal'))
					->description(__('Personal information'))
					->aside()
					->relationship(name: 'profile')
					->schema([
						TextEntry::make('first_name')
							->label(__('First name')),
						TextEntry::make('last_name')
							->label(__('Last name')),
						TextEntry::make('contact')
							->label(__('Phone number')),
						ImageEntry::make('photo')
							->label(__('Profile picture')),
						TextEntry::make('dob')
							->label(__('Date of birth')),
						TextEntry::make('country')
							->label(__('Country')),
						TextEntry::make('city')
							->label(__('City')),
						TextEntry::make('address')
							->label(__('Address')),
						TextEntry::make('bio')
							->label(__('Bio')),
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
			'index' => Pages\ListUsers::route('/'),
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
