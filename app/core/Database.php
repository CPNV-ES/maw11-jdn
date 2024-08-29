<?php

/**
 * Class for the Database
 */
class Database
{
	/**
	 * PDO property for the database
	 * @var Database::pdo $pdo
	 */
	private $pdo;

	/**
	 * Constructor of the Database class
	 * @param mixed $config
	 */
	public function __construct($config)
	{
		$dsn = "{$config['driver']}:{$config['database']}";
		try {
			$this->pdo = new PDO($dsn);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			die("Erreur de connexion à la base de données : " . $e->getMessage());
		}
	}

	/**
	 * Query function to make database queries
	 * @param mixed $sql
	 * @param mixed $params
	 * @return bool|PDOStatement
	 */
	public function query($sql, $params = [])
	{
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt;
	}

	/**
	 * Fetch function to fetch specific data from database
	 * @param mixed $sql
	 * @param mixed $params
	 * @return mixed
	 */
	public function fetch($sql, $params = [])
	{
		$stmt = $this->query($sql, $params);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Fetch function to fetch all data from database
	 * @param mixed $sql
	 * @param mixed $params
	 * @return array
	 */
	public function fetchAll($sql, $params = [])
	{
		$stmt = $this->query($sql, $params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
