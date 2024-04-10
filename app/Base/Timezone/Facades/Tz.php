<?php

namespace Base\Timezone\Facades;

use Illuminate\Support\Facades\Facade;

class Tz extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'tz';
	}
}