<?php

/**
 * Class for the default Model
 */
class Model
{
	/**
	 * Database connection
	 * @var PDO $db
	 */
	protected $db;

	/**
	 * Constructor of Model class
	 */
	public function __construct()
	{
		// Load the database configuration
		$config = require __DIR__ . '/../config/config.php';

		// Intialize the database connection
		$this->db = Database::getInstance($config['db'])->getConnection();
	}
}
