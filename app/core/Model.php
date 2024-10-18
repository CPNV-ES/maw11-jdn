<?php

require_once APP_DIR . '/core/Database.php';

/**
 * Class for the default Model
 */
class Model
{
    /**
     * Database instance
     * @var Database
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
}
