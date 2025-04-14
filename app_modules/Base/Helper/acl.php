<?php

if (!function_exists('aclhc')) {
	/**
	 * Get ACL hardcoded property name
	 * Shortcut to config('modules.base.acl.hardcoded...')
	 * 
	 * @param string $propName Property name
	 * @return string 
	 */
	function aclhc(string $propName): string
	{
		$prop = $propName;

		$contents = config('modules.base.acl.hardcoded.permissions.contents');
		if (is_array($contents)) {
			foreach ( $contents as $content ) {
				if ($content && $content == $propName && $prop = config('modules.base.acl.hardcoded.permissions.contents.' . $propName)) {

					return $prop;
				}
			}
		}

		$names = config('modules.base.acl.hardcoded.permissions.names');
		if (is_array($names)) {
			foreach ( $names as $name ) {
				if ($name && $name == $propName && $prop = config('modules.base.acl.hardcoded.permissions.names.' . $propName)) {

					return $prop;
				}
			}
		}

		$roles = config('modules.base.acl.hardcoded.roles');
		if (is_array($roles)) {
			foreach ( $roles as $role ) {
				if ($role && $role == $propName && $prop = config('modules.base.acl.hardcoded.roles.' . $propName)) {

					return $prop;
				}
			}
		}

		return $prop;
	}
}