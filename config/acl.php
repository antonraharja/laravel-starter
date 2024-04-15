<?php

return [

	/**
	 * Permission types
	 */
	'permissions' => [
		'BUNDLE' => \Base\ACL\Checkers\Bundle::class,
		'METHOD' => \Base\ACL\Checkers\Method::class,
		'TAG' => \Base\ACL\Checkers\Tag::class,
		'IP' => \Base\ACL\Checkers\IP::class,
	],

	/**
	 * Only when using alternative variable for $_SERVER['REMOTE_ADDR]
	 */
	'remotes' => [
		'REMOTE_ADDR',
	],

	/**
	 * List of available and enabled method bundles
	 */
	'bundles' => [
		'general',
		'role',
		'token',
		'user',
		'permission',
		'profile',
	],

	/**
	 * Default method names following bundles
	 * Ref: https://filamentphp.com/docs/3.x/panels/resources/getting-started#authorization
	 */
	'methods' => [
		'create',			// create/store record
		'delete',			// delete/destroy record
		'deleteany',		// bulk delete records
		'forcedelete',		// force delete records
		'forcedeleteany',	// force bulk delete records
		'reorder',			// reorder record
		'restore',			// restore record
		'restoreany',		// bulk restore records
		'update',			// update/edit record
		'view',				// view record
		'viewany',			// entry access to records
	],

	/**
	 * List of hardcoded roles or permissions name/content
	 */
	'hardcoded' => [
		'roles' => [

			// not yet used but will be use to define administrator role
			'ADMIN',
		],
		'permission' => [
			'names' => [

				// not yet used but will be for limiting access from LAN IPs/networks
				'LAN',
			],
			'contents' => [

				// used in create/edit users and edit profile
				'change-username' => 'change-username',

				// used in create/edit users
				'change-veriried-at' => 'change-verified-at',
			],
		],
	],
];
