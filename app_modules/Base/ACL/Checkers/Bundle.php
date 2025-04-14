<?php

namespace Base\ACL\Checkers;

use Closure;
use Filament\Forms;
use Base\ACL\Helper;

class Bundle extends BaseChecker
{
	public function check(string|array $content): bool
	{
		$items = (new Helper)->formatInputs($content);

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
		$items = (new Helper)->formatInputs($content);

		if (!$items) {

			return false;
		}

		foreach ( $items as $item ) {
			// match alphanumeric
			if (preg_match('/[^\p{L}]+/u', $item)) {
				$this->invalidEntry = $item;
				$this->invalidMessage = __('Allowed characters are alphanumerics only');

				return false;
			}
		}

		$this->invalidEntry = null;

		return true;
	}

	public function getPermissionContentForm(): array
	{
		return [
			Forms\Components\Select::make('content')
				->label(__('Content'))
				->multiple()
				->options(function () {
					$options = [];
					$bundles = config('modules.base.acl.bundles');
					if ($bundles ?? []) {
						foreach ( $bundles as $bundle ) {
							$options[$bundle] = $bundle;
						}
					}
					return $options;
				})
				->native(false)
				->searchable()
				->placeholder(__('Select permission bundle'))
				->hint(__(''))
				->rule(
					function (): Closure {
						return function (string $attribute, $value, Closure $fail) {
							if (!($this->validate($value) === true)) {
								$fail(__('Error invalid value') . ' "' . $this->invalidEntry . '". ' . $this->invalidMessage . '.');
							}
						};
					}
				),
		];
	}
}