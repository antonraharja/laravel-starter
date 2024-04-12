<?php

namespace Base\ACL\Checkers;

use Base\ACL\Config;

class Bundle implements CheckerInterface
{
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
		$bundles = $content;

		$permittedBundles = [];
		$newBundles = [];

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

		$bundles = preg_split('/\s/', strtolower($bundles));
		$bundles = array_unique($bundles);
		foreach ( $bundles as $bundle ) {
			if ($bundle = trim($bundle)) {
				$newBundles[] = strtolower(trim($bundle));
			}
		}

		if (!$newBundles) {

			return false;
		}

		return (bool) array_intersect($permittedBundles, $newBundles);
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