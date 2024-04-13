<?php

namespace Base\ACL\Traits;

trait ACLHelper
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
}