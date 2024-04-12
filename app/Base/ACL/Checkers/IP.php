<?php

namespace Base\ACL\Checkers;

use Base\ACL\Config;

class IP implements CheckerInterface
{
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
		if (is_array($content)) {
			$items = '';
			foreach ( $content as $item ) {
				if ($item = strtolower(trim($item))) {
					$items .= $item . ' ';
				}
			}
			$items = trim($items);
		}

		$items = preg_split('/\s/', strtolower($content));

		$items = array_unique($items);

		foreach ( $items as $item ) {
			if (matchIP($item, $_SERVER['REMOTE_ADDR'])) {
				$this->permittedEntry = $item;

				return true;
			}
		}

		$this->permittedEntry = null;

		return false;
	}

	public function validate(string|array $content): bool
	{
		$items = [];

		if (is_array($content)) {
			foreach ( $content as $item ) {
				$items[] = $item;
			}
		} else if (is_string($content)) {
			$items = preg_split('/\s/', strtolower($content));
		}
		$items = array_unique($items);

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