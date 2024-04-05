<?php

namespace Base\General\Facades;

use Illuminate\Support\Facades\Facade;

class General extends Facade
{
	protected static function getFacadeAccessor()
	{
		return '\Base\General\General';
	}
}