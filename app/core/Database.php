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
        $dbConfig = $config['db'];

        $dsn = "{$dbConfig['driver']}:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];

        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException("Connection error to the database: " . $e->getMessage());
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
     * Query method to make simple database queries
     * @param mixed $query - sql query
     * @return bool|PDOStatement - statement of the query
     */
    public function querySimpleExecute($query)
    {
        $req = $this->pdo->query($query);

        return $req;
    }

    /**
     * Query method to make database queries with binds
     * @param mixed $query - sql query
     * @param mixed $binds - binds of the query
     * @return bool|PDOStatement - statement of the query
     */
    public function queryPrepareExecute($query, $binds = [])
    {
        $req = $this->pdo->prepare($query);

        foreach ($binds as $key => $element) {
            $req->bindValue($key, $element['value'], $element['type']);
        }

        $req->execute();

        return $req;
    }

    /**
     * Fetch method to fetch specific data from database
     * @param mixed $query - sql query
     * @return mixed - statement of the query
     */
    public function fetch($query)
    {
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch method to fetch all data from database
     * @param mixed $query - sql query
     * @return array - statement of the query
     */
    public function fetchAll($query)
    {
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Method to return last id inserted in the database
     * @return bool|string
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
