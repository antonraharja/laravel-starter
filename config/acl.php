<?php

return [

	/**
	 * List of all enabled models
	 */
	'models' => [
		'api',
		'general',
		'role',
		'user',
		'permission',
		'profile',
	],

	/**
	 * Policy types
	 */
	'permissions' => [
		'POLICY' => \Base\ACL\Checkers\Policy::class,
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
