<?php

namespace App\Base\ACL\Checkers;

use App\Base\ACL\Config;

class Policy implements CheckerInterface
{
	private Config $config;

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	public function check(string $type, string $content): bool
	{
		$policies = $content;

		$permittedPolicies = [];
		$newPolicies = [];

		$models = $this->config->currentPermissions[$type];

		if (!(is_array($models) && $models)) {
			return false;
		}

		foreach ( $models as $model ) {
			foreach ( $this->config->allDefaultMethods as $method ) {
				$permittedPolicies[] = strtolower($model . '.' . $method);
			}
		}

		$policies = preg_split('/\s/', strtolower($policies));
		$policies = array_unique($policies);
		foreach ( $policies as $policy ) {
			if ($policy = trim($policy)) {
				$newPolicies[] = strtolower(trim($policy));
			}
		}

		if (!$newPolicies) {
			return false;
		}

		return (bool) array_intersect($permittedPolicies, $newPolicies);
	}
}