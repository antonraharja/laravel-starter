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

	private string $permissionType = 'BUNDLE';

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

		$permittedBundles = [];

		if (isset($this->config->currentPermissions[$this->permissionType])) {
			$bundles = $this->config->currentPermissions[$this->permissionType];
		} else {

			return false;
		}

		if (!(is_array($bundles) && $bundles)) {

			return false;
		}

		foreach ( $bundles as $bundle ) {
			foreach ( $this->config->allDefaultMethods as $method ) {
				$permittedBundles[] = strtolower($bundle . '.' . $method);
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