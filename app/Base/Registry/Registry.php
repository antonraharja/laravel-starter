<?php

namespace Base\Registry;

use Base\Registry\Models\Registry;
use Illuminate\Support\Collection;


class SimpleRegistry implements RegistryInterface
{
	private array $displayedFields = [
		'group',
		'keyword',
		'content',
	];

	private Collection $data;

	private array $dataArray = [];

	private array $dataNestedArray = [];

	public function __construct()
	{
		$this->data = new Collection;
	}

	public function all(): SimpleRegistry
	{
		$this->data = Registry::select($this->displayedFields)->get();

		return $this;
	}

	public function get(string $group, string $keyword = ''): SimpleRegistry
	{
		$this->data = new Collection;

		if ($group && $keyword) {
			$this->data = Registry::where([
				'group' => $group,
				'keyword' => $keyword
			])->select($this->displayedFields)->get();
		} else if ($group) {
			$this->data = Registry::where([
				'group' => $group
			])->select($this->displayedFields)->get();
		}

		return $this;
	}

	public function toArray(): array
	{
		$this->dataArray = $this->data->toArray();

		return $this->dataArray;
	}

	public function toNestedArray(): array
	{
		$this->dataNestedArray = [];
		$this->dataArray = $this->data->toArray();

		foreach ( $this->dataArray as $row ) {
			$this->dataNestedArray[$row['group']][$row['keyword']] = $row['content'];
		}

		return $this->dataNestedArray;
	}

	public function getGroup(string|array $group): array
	{
		$data = [];

		if (is_array($group)) {
			foreach ( $group as $item ) {
				$data = array_merge($data, $this->get($item)->toNestedArray());
			}
		} else {
			$data = array_merge($this->get($group)->toNestedArray());
		}

		return $data;
	}

	public function getContent(string $group, string $keyword)
	{
		$content = $this->get($group, $keyword)->toNestedArray();

		return isset($content[$group][$keyword]) ? $content[$group][$keyword] : null;
	}
}