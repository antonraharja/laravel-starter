<?php

namespace Base\Registry;

use Illuminate\Support\Collection;

interface RegistryInterface
{
	public function __construct();

	public function all(): SimpleRegistry;

	public function get(string $group, string $keyword = ''): SimpleRegistry;

	public function toArray(): array;

	public function toNestedArray(): array;

	public function getGroup(string|array $group): array;

	public function getContent(string $group, string $keyword);

	public function getAll(): array;

	public function save(array $data): SimpleRegistry;

	public function saved(): ?bool;

	public function savedData(): null|string|array;
}