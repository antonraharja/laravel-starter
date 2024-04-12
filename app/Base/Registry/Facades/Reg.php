<?php

namespace Base\Registry\Facades;

use Illuminate\Support\Facades\Facade;

class Reg extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'reg';
	}
}