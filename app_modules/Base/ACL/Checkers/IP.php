<?php

namespace Base\ACL\Checkers;

use Closure;
use Filament\Forms;
use Base\ACL\Helper;

class IP extends BaseChecker
{
	public function check(string|array $content): bool
	{
		$items = (new Helper)->formatInputs($content);

		if (!$items) {

			return false;
		}

		foreach ( $items as $item ) {
			$remotes = config('modules.base.acl.remotes');
			if (is_array($remotes)) {
				foreach ( $remotes as $addr ) {
					if (isset($_SERVER[$addr])) {
						if (matchIP($item, $_SERVER[$addr])) {
							$this->permittedEntry = $item;

							return true;
						}
					}
				}
			}
		}

		$this->permittedEntry = null;

		return false;
	}

	public function validate(string|array $content): bool
	{
		$items = (new Helper)->formatInputs($content);

		if (!$items) {

			return false;
		}

		foreach ( $items as $item ) {
			if (!isIP($item)) {
				$this->invalidEntry = $item;
				$this->invalidMessage = __('Allowed only valid IP address or network');

				return false;
			}
		}

		$this->invalidEntry = null;

		return true;
	}

	public function getPermissionContentForm(): array
	{
		return [
			Forms\Components\TagsInput::make('content')
				->label(__('Content'))
				->reorderable()
				->placeholder(__('Enter IP address or network'))
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