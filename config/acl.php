<?php

return [

	/**
	 * List of enabled method bundles
	 */
	'bundles' => [
		'api',
		'general',
		'registry',
		'role',
		'user',
		'permission',
		'profile',
	],

	/**
	 * Permission types
	 */
	'permissions' => [
		'BUNDLE' => \Base\ACL\Checkers\Bundle::class,
		'METHOD' => \Base\ACL\Checkers\Label::class,
		'TAG' => \Base\ACL\Checkers\Label::class,
	],

	/**
	 * Default method names
	 * Ref: https://filamentphp.com/docs/3.x/panels/resources/getting-started#authorization
	 */
	'methods' => [
		'create',			// create record
		'delete',			// delete record
		'deleteany',		// bulk delete records
		'forcedelete',		// force delete records
		'forcedeleteany',	// force bulk delete records
		'reorder',			// reorder record
		'restore',			// restore record
		'restoreany',		// bulk restore records
		'update',			// update record
		'view',				// view record
		'viewany',			// entry access to records
	],
];
