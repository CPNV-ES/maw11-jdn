<?php

require_once APP_DIR . '/core/Database.php';

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
     * Query method to make simple database queries
     * @param mixed $query - sql query
     * @return bool|PDOStatement - statement of the query
     */
    public function querySimpleExecute($query)
    {
        return $this->db->querySimpleExecute($query);
    }

    /**
     * Query method to make database queries with binds
     * @param mixed $query - sql query
     * @param mixed $binds - binds of the query
     * @return bool|PDOStatement - statement of the query
     */
    public function queryPrepareExecute($query, $binds = [])
    {
        return $this->db->queryPrepareExecute($query, $binds);
    }

    /**
     * Fetch method to fetch specific data from database
     * @param mixed $query - sql query
     * @return mixed - statement of the query
     */
    protected function fetch($sql)
    {
        return $this->db->fetch($sql);
    }

    /**
     * Fetch method to fetch all data from database
     * @param mixed $query - sql query
     * @return array - statement of the query
     */
    protected function fetchAll($sql)
    {
        return $this->db->fetchAll($sql);
    }
}
