<?php

/**
 * @file Database.php
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 19.12.2024
 * @description Singleton class for managing database connections and operations.
 *
 * @details Provides methods for connecting to a database using PDO, executing queries,
 *          and retrieving data. Implements the Singleton pattern to ensure a single
 *          instance of the database connection throughout the application.
 */
class Database
{
    /**
     * Singleton instance of the Database class
     * @var Database|null
     */
    private static $instance = null;

    /**
     * PDO instance for database connection
     * @var PDO
     */
    private $pdo;

    /**
     * Constructor of the Database class
     * 
     * @param array $config Configuration array containing database connection details.
     * @throws PDOException If the connection to the database fails.
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
     * Retrieves the singleton instance of the Database class.
     * 
     * @param array $config Configuration array for database connection.
     * @return Database Singleton instance of the Database class.
     */
    public static function getInstance($config)
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * Executes a simple SQL query without parameter binding.
     * 
     * @param string $query The SQL query string.
     * @return bool|PDOStatement The resulting statement object or false on failure.
     */
    public function querySimpleExecute($query)
    {
        $req = $this->pdo->query($query);

        return $req;
    }

    /**
     * Executes a prepared SQL query with parameter binding.
     * 
     * @param string $query The SQL query string with placeholders.
     * @param array $binds An associative array of placeholders and their values:
     *                     - 'value': The value to bind
     *                     - 'type': The PDO type of the value (e.g., PDO::PARAM_STR)
     * @return bool|PDOStatement The resulting statement object or false on failure.
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
     * Fetches a single row of data from the result of a query.
     * 
     * @param PDOStatement $query The executed query object.
     * @return array|false An associative array of the fetched row or false if no rows remain.
     */
    public function fetch($query)
    {
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches all rows of data from the result of a query.
     * 
     * @param PDOStatement $query The executed query object.
     * @return array An array of associative arrays representing all fetched rows.
     */
    public function fetchAll($query)
    {
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the ID of the last inserted row in the database.
     * 
     * @return string The ID of the last inserted row.
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
