<?php

namespace Base\ACL\Checkers;

use Base\ACL\Config;

interface CheckerInterface
{
	public function __construct(Config $config);

	public function check(string $type, string $content): bool;
}