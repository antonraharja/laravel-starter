<?php

namespace Base\ACL;

use Base\ACL\Facades\ACL;

class Helper
{
	public function formatInputs(array|string $content): array
	{
		$items = [];

		if (is_array($content)) {
			foreach ( $content as $item ) {
				$items[] = strtolower(trim($item));
			}
		} else if (is_string($content)) {
			$items = preg_split('/\s/', strtolower($content));
		}

		return array_unique($items);
	}

	public function getPermissionContentForm(?string $permissionType): array
	{
		$form = [];

		if ($permissionType ?? null) {
			$handlerClass = config('modules.base.acl.permissions');
			if ($handlerClass = $handlerClass[$permissionType] ?? null) {
				if (class_exists($handlerClass)) {
					$handler = new $handlerClass($permissionType, ACL::config());
					$form = $handler->getPermissionContentForm();
				}
			}
		}

		return $form;
	}
}