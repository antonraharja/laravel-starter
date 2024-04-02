<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use App\Filament\Resources\UserResource;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
	protected ?string $maxWidth = '4xl';

	public function form(Form $form): Form
	{
		return UserResource::form($form);
	}
}