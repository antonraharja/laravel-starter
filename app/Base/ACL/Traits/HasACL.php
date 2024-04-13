<?php

namespace Base\ACL\Traits;

use Base\ACL\Config;

trait HasACL
{
	use ACLHelper;

	public function config(): Config
	{
		$config = new Config;

		$config->populate();

		return $config;
	}

	public function have(string|array $permissions): bool
	{
		$permissions = $this->formatInputs($permissions);

		if (!$permissions) {

			return false;
		}

		$config = $this->config();

		foreach ( $config->currentPermissionsNames as $currentPermissionName ) {
			foreach ( $permissions as $item ) {
				if ($item && strtoupper($item) === strtoupper($currentPermissionName)) {

					return true;
				}
			}
		}

		$check = false;

		foreach ( $config->allPermissionCheckers as $type => $handlerClass ) {
			$type = strtoupper(trim($type));

			if (isset($config->currentPermissions[$type]) && is_array($config->currentPermissions[$type]) && $config->currentPermissions[$type]) {
				$check = (new $handlerClass($type, $config))->check($permissions) || $check;
			}
		}

		return $check;
	}

	public function dontHave(string $permissions): bool
	{
		return !$this->have($permissions);
	}

	public function role(string $role): bool
	{
		return (bool) in_array(strtoupper($role), $this->getRoles());
	}

	public function getRoles(): array
	{
		return array_unique($this->config()->currentRoles);
	}

	public function getPermissions(?string $type): array
	{
		$permissions = [];

		if (!(isset($type) && is_string($type))) {

			return $permissions;
		}

		$type = strtoupper(trim($type));

		$currentPermissions = $this->config()->currentPermissions;

		if ($type === null) {
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