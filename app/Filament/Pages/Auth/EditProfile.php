<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use App\Filament\Clusters\Settings\Resources\UserResource;

class EditProfile extends BaseEditProfile
{
	protected ?string $maxWidth = '4xl';

	public function form(Form $form): Form
	{
		return UserResource::form($form);
	}
}