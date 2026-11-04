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
	 * List of available method names
	 */
	'methods' => [
		'create',			// create/store own records
		'createany',		// create/store any records
		'restore',			// restore own records
		'restoreany',		// restore any records
		'update',			// update/edit own records
		'updateany',		// update/edit any records
		'delete',			// delete/destroy own records
		'deleteany',		// delete/destroy any records
		'forcedelete',		// force delete/destroy own records
		'forcedeleteany',	// force delete/destroy any records
		'view',				// view own records
		'viewany',			// view any records
		'reorder',			// reorder records
	],

	/**
	 * List of hardcoded roles or permissions name/content
	 */
	'hardcoded' => [
		'roles' => [

			// not yet used but will be use to define administrator role
			'ADMIN' => 'ADMIN',
		],

		'permissions' => [
			'names' => [

				// not yet used but will be for limiting access from LAN IPs/networks
				'LAN' => 'LAN',

				// allows changing of username
				'CHANGE_USERNAME' => 'CHANGE_USERNAME',

				// allows changing of email verified at, marking a verified account
				'CHANGE_VERIFIED_AT' => 'CHANGE_VERIFIED_AT',
			],

			'contents' => [

				// used in create/edit users and edit profile
				'change-username' => 'change-username',

				// used in create/edit users
				'change-verified-at' => 'change-verified-at',
			],
		],
	],
];
