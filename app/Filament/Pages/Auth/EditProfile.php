<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Resources\UserResource;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
	public function form(Form $form): Form
	{
		return UserResource::form($form);
	}
}