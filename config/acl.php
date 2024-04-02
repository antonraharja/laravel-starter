<?php

return [

	/**
	 * List of all models
	 */
	'models' => [
		'api',
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
	 */
	'methods' => [
		'index',
		'viewany',
		'view',
		'create',
		'update',
		'delete',
		'restore',
		'forcedelete',
		'store',
		'edit',
		'destroy',
		'list',
	],
];
