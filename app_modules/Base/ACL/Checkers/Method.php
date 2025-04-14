<?php

namespace Base\ACL\Checkers;

use Closure;
use Filament\Forms;

class Method extends BaseChecker
{
	public function getPermissionContentOptions(): array
	{
		$options = [];

		$bundles = config('modules.base.acl.bundles');
		$methods = config('modules.base.acl.methods');

		if ($bundles ?? null && $methods ?? null) {
			foreach ( $bundles as $bundle ) {
				foreach ( $methods as $method ) {
					$option = $bundle . '.' . $method;
					$options[$option] = $option;
				}
			}
		}

		return $options;
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
					$methods = config('modules.base.acl.methods');
					if ($bundles ?? null && $methods ?? null) {
						foreach ( $bundles as $bundle ) {
							foreach ( $methods as $method ) {
								$option = $bundle . '.' . $method;
								$options[$option] = $option;
							}
						}
					}
					return $options;
				})
				->native(false)
				->searchable()
				->placeholder(__('Select permission methods'))
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