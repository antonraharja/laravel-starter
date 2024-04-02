<?php

namespace Base\Token\Models;

use Laravel\Sanctum\PersonalAccessToken;

class Token extends PersonalAccessToken
{
	protected $table = 'personal_access_tokens';
}
