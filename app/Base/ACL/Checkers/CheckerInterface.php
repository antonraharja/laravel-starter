<?php

namespace App\Base\ACL\Checkers;

use App\Base\ACL\Config;

interface CheckerInterface
{
	public function __construct(Config $config);

	public function check(string $type, string $content): bool;
}