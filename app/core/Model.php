<?php

/**
 * @file Model.php
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 19.12.2024
 * @description Base class for all models in the application.
 *
 * @details Provides a shared database connection for all models by leveraging
 *          the singleton pattern implemented in the `Database` class. Acts as
 *          the foundation for all application-specific models.
 */

require_once APP_DIR . '/core/Database.php';

class Model
{
    /**
     * Instance of the Database class for executing queries
     * @var Database
     */
    protected $db;

    /**
     * Constructor of the Model class
     * 
     * @details Initializes a connection to the database using the configuration
     *          defined in the application's configuration file.
     */
    public function __construct()
    {
        $config = require CONFIG_DIR . '/config.php';

        $this->db = Database::getInstance($config);
    }
}
