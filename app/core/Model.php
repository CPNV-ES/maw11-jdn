<?php

require_once 'Database.php';

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
        $config = require __DIR__ . '../../../config/config.php';

        // Intialize the database connection
        $this->db = Database::getInstance($config);
    }

    /**
     * Query method to make database queries
     * @param mixed $query - sql query
     * @param mixed $params - parameters of the query
     * @return bool|PDOStatement - statement of the query
     */
    protected function query($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }

    /**
     * Fetch method to fetch specific data from database
     * @param mixed $query - sql query
     * @param mixed $params - parameter of the query
     * @return mixed - statement of the query
     */
    protected function fetch($sql, $params = [])
    {
        return $this->db->fetch($sql, $params);
    }

    /**
     * Fetch method to fetch all data from database
     * @param mixed $query - sql query
     * @param mixed $params - parameter of the query
     * @return array - statement of the query
     */
    protected function fetchAll($sql, $params = [])
    {
        return $this->db->fetchAll($sql, $params);
    }
}
