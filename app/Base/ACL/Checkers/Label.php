<?php

namespace App\Base\ACL\Checkers;

use App\Base\ACL\Config;

class Label implements CheckerInterface
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
			foreach ( $this->config->currentPermissions[$type] as $permitted ) {
				if (strtolower($permitted) === $item) {
					return true;
				}
			}
		}

		return false;
	}
}