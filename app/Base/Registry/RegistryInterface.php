<?php

namespace Base\Registry;

interface RegistryInterface
{
	public function __construct();

	public function all(): SimpleRegistry;

	public function get(string $group, string $keyword = ''): SimpleRegistry;

	public function toArray(): array;

	public function toNestedArray(): array;

	public function getGroup(string|array $group): array;

	public function getContent(string $group, string $keyword);
}