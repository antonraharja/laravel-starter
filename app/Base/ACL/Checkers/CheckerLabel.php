<?php

namespace Base\ACL\Checkers;

use Base\ACL\Config;
use Base\ACL\Traits\ACLHelper;

class CheckerLabel implements CheckerInterface
{
	use ACLHelper;

	private Config $config;

	private ?string $permittedEntry = null;

	private ?string $invalidEntry = null;

	public const TYPE = 'LABEL';

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	public function check(string|array $content): bool
	{
		$items = $this->formatInputs($content);

		if (!$items) {

			return false;
		}

		foreach ( $items as $item ) {
			if (isset($this->config->currentPermissions[self::TYPE])) {
				foreach ( $this->config->currentPermissions[self::TYPE] as $permitted ) {
					if (strtolower($permitted) === $item) {
						$this->permittedEntry = $item;

						return true;
					}
				}
			}
		}

		$this->permittedEntry = null;

		return false;
	}

	public function validate(string|array $content): bool
	{
		$items = $this->formatInputs($content);

		if (!$items) {

			return false;
		}

		foreach ( $items as $item ) {
			// match alphanumeric, dot and dash
			if (preg_match('/[^\p{L}\.\-]+/u', $item)) {
				$this->invalidEntry = $item;

				return false;
			}
		}

		$this->invalidEntry = null;

		return true;
	}

	public function getPermittedEntry(): ?string
	{
		return $this->permittedEntry;
	}

	public function getInvalidEntry(): ?string
	{
		return $this->invalidEntry;
	}
}