<?php

namespace Base\Registry;

use Exception;
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

	private array $lastSaved = [];

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
		$newDataArray = [];

		$this->dataArray = $this->data->toArray();
		foreach ( $this->dataArray as $row ) {
			$newDataArray[] = [
				'group' => $row['group'],
				'keyword' => $row['keyword'],
				'content' => unserialize($row['content']),
			];
		}

		return $newDataArray;
	}

	public function toNestedArray(): array
	{
		$this->dataNestedArray = [];

		foreach ( $this->toArray() as $row ) {
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

	public function getContent(string $group, string $keyword): null|string|array
	{
		$content = $this->get($group, $keyword)->toNestedArray();

		return isset($content[$group][$keyword]) ? $content[$group][$keyword] : null;
	}

	public function getAll(): array
	{
		return $this->all()->toNestedArray();
	}

	public function save(array $data): SimpleRegistry
	{
		try {
			$preparedData = [];

			foreach ( $data as $group => $rows ) {
				foreach ( $rows as $keyword => $content ) {
					if ($group && $keyword) {

						$preparedData[] = [
							'group' => $group,
							'keyword' => $keyword,
							'content' => serialize($content),
						];
					}
				}
			}

			if ($preparedData) {
				$this->lastSaved = [
					'status' => true,
					'data' => [
						$preparedData,
						Registry::upsert($preparedData, ['group', 'keyword'], ['content']),
					],
				];
			} else {
				$this->lastSaved = [
					'status' => false,
					'data' => null,
				];
			}
		} catch (Exception $e) {
			$this->lastSaved = [
				'status' => false,
				'data' => $e->getMessage(),
			];
		}

		return $this;
	}

	public function saved(): ?bool
	{
		return is_bool($this->lastSaved['status']) ? $this->lastSaved['status'] : null;
	}

	public function savedData(): array|string|null
	{
		return isset($this->lastSaved['data']) ? $this->lastSaved['data'] : null;
	}
}