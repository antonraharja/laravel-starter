<?php

namespace Base\ACL\Facades;

use Illuminate\Support\Facades\Facade;

class ACL extends Facade
{
	protected static function getFacadeAccessor()
	{
		return '\Base\ACL\Services\ACLServices';
	}
}