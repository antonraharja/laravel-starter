<?php

namespace Base\ACL\Checkers;

use Base\ACL\Config;

interface CheckerInterface
{
	public function __construct(string $type, Config $config);

	public function check(string|array $content): bool;

	public function validate(string|array $content): bool;

	public function getPermittedEntry(): ?string;

	public function getInvalidEntry(): ?string;

	public function getInvalidMessage(): ?string;
}