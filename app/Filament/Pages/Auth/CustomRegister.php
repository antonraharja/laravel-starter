<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Register;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;

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

	protected function getCredentialsFromFormData(array $data): array
	{
		$login_type = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		return [
			$login_type => $data['login'],
			'password' => $data['password'],
		];
	}

	protected function throwFailureValidationException(): never
	{
		throw ValidationException::withMessages([
			'data.login' => __('filament-panels::pages/auth/login.messages.failed'),
		]);
	}
}