<?php

namespace Base\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Base\Filament\Clusters\Settings\Resources\UserResource\Pages\EditUser;

class CustomEditProfile extends EditProfile
{
	protected Width|string|null $maxWidth = '4xl';

	public function form(Schema $schema): Schema
	{
		return $schema
			->components(EditUser::getEditForm());
	}
}