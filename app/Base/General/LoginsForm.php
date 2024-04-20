<?php

namespace App\Base\General;

use Base\ACL\Facades\ACL;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;

class LoginsForm
{
	public function get(): array
	{
		return [
			Section::make(__('Login'))
				->description(__('Default settings for login'))
				->aside()
				->schema([
					Group::make([
						Select::make('default_register_roles')
							->label(__('Default register roles'))
							->multiple()
							->options(function () {
								return ACL::config()->allRolesSelect;
							})
							->preload()
							->native(false)
							->disabled(ACL::dontHave('role.viewany'))
							->hidden(ACL::dontHave('role.viewany')),
						Toggle::make('enable_register')
							->label(__('Enable register')),
						Toggle::make('enable_password_reset')
							->label(__('Enable password reset')),
						Toggle::make('enable_email_verification')
							->label(__('Enable email verification')),
					])->statePath('logins')
				])->columns(2)
		];
	}
}