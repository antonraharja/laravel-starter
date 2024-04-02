<?php

namespace Base\ACL\Traits;

use Base\ACL\Config;

trait HasACL
{
	public function config(): Config
	{
		$config = new Config;

		$config->populate();

		return $config;
	}

	public function permit(string $permissions): bool
	{
		$check = false;

		$config = $this->config();

		foreach ( $config->allPermissionCheckers as $type => $handlerClass ) {
			$type = strtoupper(trim($type));

			if (isset($config->currentPermissions[$type]) && is_array($config->currentPermissions[$type]) && $config->currentPermissions[$type]) {
				$check = (new $handlerClass($config))->check($type, $permissions) || $check;
			}
		}

		return $check;
	}

	public function role(string $role): bool
	{
		return (bool) in_array(strtoupper($role), $this->getRoles());
	}

	public function getRoles(): array
	{
		return array_unique($this->config()->currentRoles);
	}

	public function getPermissions(string $type = '*'): array
	{
		$permissions = [];

		if (!(isset($type) && is_string($type))) {

			return $permissions;
		}

		$type = strtoupper(trim($type));

		$currentPermissions = $this->config()->currentPermissions;

		if ($type === '*') {
			foreach ( $currentPermissions as $types ) {
				foreach ( $types as $content ) {
					$permissions[] = $content;
				}
			}
		} else {
			if (isset($currentPermissions[$type]) && is_array($currentPermissions[$type])) {
				foreach ( $currentPermissions[$type] as $content ) {
					$permissions[] = $content;
				}
			}
		}

		$permissions = array_unique($permissions);

		return $permissions;
	}
}