<?php

namespace Base\General\Traits;

use Base\General\Facades\General;

trait HasLogins
{
	public function getDefaultRegisterRoles(): array
	{
		$defaultRegisterRoles = General::getContent('logins', 'default_register_roles');

		return is_array($defaultRegisterRoles) ? $defaultRegisterRoles : [];
	}

	public function getEnableRegister(): bool
	{
		$enableRegister = General::getContent('logins', 'enable_register');

		return $enableRegister ?? false;
	}

	public function getEnablePasswordReset(): bool
	{
		$enablePasswordReset = General::getContent('logins', 'enable_password_reset');

		return $enablePasswordReset ?? false;
	}

	public function getEnableEmailVerification(): bool
	{
		$enableEmailVerification = General::getContent('logins', 'enable_email_verification');

		return $enableEmailVerification ?? false;
	}
}