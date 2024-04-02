<?php

namespace App\Base\ACL;

class Config
{
	public array $allPermissionCheckers = [];

	public array $allPermissionTypes = [];

	public array $allDefaultMethods = [];

	public array $currentRoles = [];

	public array $currentPermissions = [];

	public function populate(): Config
	{
		$data = config('acl.permissions');
		$types = [];
		$checkers = [];
		foreach ( $data as $key => $val ) {
			$key = strtoupper(trim($key));
			$types[$key] = __(ucwords($key));
			$checkers[$key] = $val;
		}
		$this->allPermissionTypes = $types;
		$this->allPermissionCheckers = $checkers;

		$data = config('acl.methods');
		$methods = [];
		foreach ( $data as $method ) {
			$methods[] = strtolower(trim($method));
		}
		$this->allDefaultMethods = $methods;

		$this->getUpdatedRolesPermissions();

		return $this;
	}

	private function getUpdatedRolesPermissions(): Config
	{
		$this->currentRoles = [];
		$this->currentPermissions = [];

		if (!isset(auth()->user()->roles)) {

			return $this;
		}

		foreach ( auth()->user()->roles as $registeredRole ) {
			if (isset($registeredRole->name) && $role = trim($registeredRole->name)) {
				$this->currentRoles[] = strtoupper($role);

				$query = $registeredRole->permissions->whereIn('type', $this->allPermissionTypes);

				foreach ( $query as $permission ) {
					if (isset($permission->type) && is_array($permission->content)) {
						foreach ( $permission->content as $content ) {
							$this->currentPermissions[strtoupper($permission->type)][] = trim($content);
						}
					}
				}
			}
		}

		return $this;
	}
}