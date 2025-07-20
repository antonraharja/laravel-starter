<?php

namespace Base\Filament\Pages\Auth;

use Filament\Forms\Form;
use Base\ACL\Models\Role;
use Base\General\Facades\General;
use Filament\Pages\Auth\Register;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;

class CustomRegister extends Register
{
	public function form(Form $form): Form
	{
		return $form
			->schema([
				TextInput::make('username')
					->label(__('Username'))
					->required()
					->maxLength(20)
					->autofocus()
					->unique($this->getUserModel()),
				$this->getEmailFormComponent(),
				$this->getPasswordFormComponent(),
				$this->getPasswordConfirmationFormComponent(),
			])
			->statePath('data');
	}

	protected function handleRegistration(array $data): Model
	{
		try {
			$user = $this->getUserModel()::create($data);

			$roles = General::getDefaultRegisterRoles();
			foreach ( $roles as $roleName ) {
				if ($roleName = trim($roleName) && $role = Role::where('name', $roleName)->get()) {
					$user->roles()->attach($role);
				}
			}

			return $user;
		} catch (\Exception $e) {
			abort(500, $e->getMessage());
		}
	}
}