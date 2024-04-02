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
		'POLICY' => \App\Base\ACL\Checkers\Policy::class,
		'METHOD' => \App\Base\ACL\Checkers\Label::class,
		'TAG' => \App\Base\ACL\Checkers\Label::class,
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
