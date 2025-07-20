<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
	protected $module;
	protected $subModule;
	protected $config;
	protected $configPath;

	public function __construct(array $attributes = [])
	{
		$className = explode('\\', get_class($this));

		$this->module = strtolower(trim($className[0]));
		$this->subModule = strtolower(trim(end($className)));
		$this->config = !empty($this->config) ? strtolower(trim($this->config)) : $this->subModule;
		$this->configPath = $this->configPath ?? 'modules.' . $this->module . '.' . $this->config;

		if (empty($this->connection)) {
			$dbConnectionConfig = '.' . $this->config . '_db_connection';
			$dbConnection = config($this->configPath . $dbConnectionConfig);
			if (!empty($dbConnection) && is_array($dbConnection)) {
				$dbConnectionName = $this->config . '_db';
				config(['database.connections.' . $dbConnectionName => $dbConnection]);
				$this->connection = $dbConnectionName;

			}
		}

		if (empty($this->table)) {
			$tableNames = '.' . $this->config . '_db_table';
			$tables = config($this->configPath . $tableNames);
			$this->table = !empty($tables[$this->subModule]) ? strtolower(trim($tables[$this->subModule])) : null;
		}

		parent::__construct($attributes);
	}
}
