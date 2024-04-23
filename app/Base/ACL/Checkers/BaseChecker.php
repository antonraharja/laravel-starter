<?php

namespace Base\ACL\Checkers;

use Closure;
use Filament\Forms;
use Base\ACL\Config;
use Base\ACL\Helper;

class BaseChecker implements BaseCheckerInterface
{
	public Config $config;

	public ?string $permittedEntry = null;

	public ?string $invalidEntry = null;

	public ?string $invalidMessage = null;

	public ?string $permissionType = null;

	public function __construct(string $type, Config $config)
	{
		$this->permissionType = $type;

		$this->config = $config;
	}

	public function check(string|array $content): bool
	{
		$items = (new Helper)->formatInputs($content);

		if (!$items) {

			return false;
		}

		foreach ( $items as $item ) {
			if (isset($this->config->currentPermissions[$this->permissionType])) {
				foreach ( $this->config->currentPermissions[$this->permissionType] as $permitted ) {
					if ($permitted && $permitted === $item) {
						$this->permittedEntry = $item;

						return true;
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
			// match alphanumeric, dot and dash
			if (preg_match('/[^\p{L}\.\-]+/u', $item)) {
				$this->invalidEntry = $item;
				$this->invalidMessage = __('Allowed characters are alphanumerics, a dash or a dot');

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
				->placeholder(__('Enter permission content'))
				->hint(__('Alphanumeric, dot, dash'))
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