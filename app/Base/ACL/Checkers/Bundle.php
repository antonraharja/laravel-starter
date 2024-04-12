<?php

namespace Base\ACL\Checkers;

use Base\ACL\Config;

class Bundle implements CheckerInterface
{
	private Config $config;

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	public function check(string $type, string $content): bool
	{
		$bundles = $content;

		$permittedBundles = [];
		$newBundles = [];

		$models = $this->config->currentPermissions[$type];

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
}