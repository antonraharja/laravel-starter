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

	private ?string $invalidMessage = null;

	private string $permissionType = 'IP';

	public function __construct(string $type, Config $config)
	{
		$this->permissionType = $type;

		$this->config = $config;
	}

	public function check(string|array $content): bool
	{
		$items = $this->formatInputs($content);

		if (!$items) {

			return false;
		}

		foreach ( $items as $item ) {
			$remotes = config('acl.remotes');
			if (is_array($remotes)) {
				foreach ( $remotes as $addr ) {
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
				$this->invalidMessage = __('Allowed only valid IP address or network');

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

	public function getInvalidMessage(): ?string
	{
		return $this->invalidMessage;
	}
}