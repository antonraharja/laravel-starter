<?php

namespace Base\ACL\Checkers;

use Base\ACL\Config;

interface BaseCheckerInterface
{
	public function __construct(string $type, Config $config);

	public function check(string|array $content): bool;

	public function validate(string|array $content): bool;

	public function getPermissionContentForm(): array;
}