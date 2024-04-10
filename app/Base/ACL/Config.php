<?php

namespace Base\ACL;

use Base\ACL\Models\Role;
use Base\ACL\Models\Permission;

class Config
{
	public array $allPermissionCheckers = [];

	public array $allPermissionTypes = [];

	public array $allDefaultMethods = [];

	public array $currentRoles = [];

	public array $currentPermissions = [];

	public array $allRoles = [];

	public array $allPermissions = [];

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

		$this->getAllRolesPermissions();

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

	private function getAllRolesPermissions(): Config
	{
		$roles = [];
		foreach ( Role::distinct()->get('name')->toArray() as $role ) {
			if (isset($role['name'])) {
				$roles[] = $role['name'];
			}
		}

		$this->allRoles = array_unique($roles);

		sort($this->allRoles);

		$permissions = [];
		foreach ( Permission::all() as $permission ) {
			foreach ( $permission->content as $item ) {
				if (isset($item)) {
					$permissions[] = $item;
				}
			}
		}
		$this->allPermissions = array_unique($permissions);

		sort($this->allPermissions);

		return $this;
	}
}