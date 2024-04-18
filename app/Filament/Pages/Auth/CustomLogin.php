<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;

class CustomLogin extends Login
{
	public function form(Form $form): Form
	{
		return $form
			->schema([
				TextInput::make('login')
					->label(__('Login'))
					->required()
					->autocomplete()
					->autofocus()
					->placeholder(__('Username or email'))
					->extraInputAttributes(['tabindex' => 1]),
				$this->getPasswordFormComponent(),
				$this->getRememberFormComponent(),
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