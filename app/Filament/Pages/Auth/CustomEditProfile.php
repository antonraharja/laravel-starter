<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile;
use App\Filament\Clusters\Settings\Resources\UserResource\Pages\EditUser;

class CustomEditProfile extends EditProfile
{
	protected ?string $maxWidth = '4xl';

	public function form(Form $form): Form
	{
		return $form
			->schema(EditUser::getEditForm());
	}
}