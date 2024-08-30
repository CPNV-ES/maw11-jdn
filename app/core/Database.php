<?php

/**
 * Class for the Database
 */
class Database
{

	/**
	 * Instance of the database
	 * @var Database $instance
	 */
	private static $instance = null;

	/**
	 * PDO property for the database
	 * @var Database::pdo $pdo
	 */
	private $pdo;

	/**
	 * Constructor of the Database class
	 * @param mixed $config - config of the database
	 */
	public function __construct($config)
	{
		$dsn = "{$config['driver']}:{$config['database']}";
		try {
			$this->pdo = new PDO($dsn);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			die("Connection error to the database : " . $e->getMessage());
		}
	}

	/**
	 * Method to get the instance of the database
	 * @param mixed $config - config of the database
	 * @return Database|null - instance of the database
	 */
	public static function getInstance($config)
	{
		if (self::$instance === null) {
			self::$instance = new self($config);
		}
		return self::$instance;
	}

	/**
	 * Method to get the pdo connection
	 * @return PDO - pdo connection
	 */
	public function getConnection()
	{
		return $this->pdo;
	}

	/**
	 * Query method to make database queries
	 * @param mixed $query - sql query
	 * @param mixed $params - parameters of the query
	 * @return bool|PDOStatement - statement of the query
	 */
	public function query($query, $params = [])
	{
		$stmt = $this->pdo->prepare($query);
		$stmt->execute($params);
		return $stmt;
	}

	/**
	 * Fetch method to fetch specific data from database
	 * @param mixed $query - sql query
	 * @param mixed $params - parameter of the query
	 * @return mixed - statement of the query
	 */
	public function fetch($query, $params = [])
	{
		$stmt = $this->query($query, $params);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Fetch method to fetch all data from database
	 * @param mixed $query - sql query
	 * @param mixed $params - parameter of the query
	 * @return array - statement of the query
	 */
	public function fetchAll($query, $params = [])
	{
		$stmt = $this->query($query, $params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
