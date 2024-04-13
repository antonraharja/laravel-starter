<?php

namespace Base\ACL\Checkers;

use Base\ACL\Config;
use Base\ACL\Traits\ACLHelper;

class Bundle implements CheckerInterface
{
	use ACLHelper;

	private Config $config;

	private ?string $permittedEntry = null;

	private ?string $invalidEntry = null;

	public const TYPE = 'BUNDLE';

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

		$permittedBundles = [];

		if (isset($this->config->currentPermissions[self::TYPE])) {
			$models = $this->config->currentPermissions[self::TYPE];
		} else {

			return false;
		}

		if (!(is_array($models) && $models)) {

			return false;
		}

		foreach ( $models as $model ) {
			foreach ( $this->config->allDefaultMethods as $method ) {
				$permittedBundles[] = strtolower($model . '.' . $method);
			}
		}

		return (bool) array_intersect($permittedBundles, $items);
	}

	public function validate(string|array $content): bool
	{
		$items = $this->formatInputs($content);

		if (!$items) {

			return false;
		}

		foreach ( $items as $item ) {
			// match alphanumeric
			if (preg_match('/[^\p{L}]+/u', $item)) {
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