<?php

namespace Base\ACL\Checkers;

use Base\ACL\Config;
use Base\ACL\Traits\ACLHelper;

class IP implements CheckerInterface
{
	use ACLHelper;

	private Config $config;

	private ?string $permittedEntry = null;

	private ?string $invalidEntry = null;

	public const TYPE = 'IP';

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
			$remoteAddr = config('acl.remoteAddr');
			if (is_array($remoteAddr)) {
				foreach ( $remoteAddr as $addr ) {
					if (isset($_SERVER[$addr])) {
						if (matchIP($item, $_SERVER[$addr])) {
							$this->permittedEntry = $item;

							return true;
						}
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
			if (!isIP($item)) {
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