<?php

namespace Base\ACL\Checkers;

use Exception;
use Base\ACL\Config;

class IP implements CheckerInterface
{
	private Config $config;

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	public function check(string $type, string $content): bool
	{
		$items = preg_split('/\s/', strtolower($content));
		$items = array_unique($items);
		foreach ( $items as $item ) {
			if (matchIP($item, $_SERVER['REMOTE_ADDR'])) {

				return true;
			}
		}

		return false;
	}
}