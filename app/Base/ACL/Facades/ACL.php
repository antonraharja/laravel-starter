<?php

namespace App\Base\ACL\Facades;

use Illuminate\Support\Facades\Facade;

class ACL extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'acl';
	}
}