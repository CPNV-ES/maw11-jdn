<?php
define('BASE_DIR', dirname(__FILE__) . '/..');
define('APP_DIR', BASE_DIR . '/app');
define('CONFIG_DIR', BASE_DIR . '/config');

require_once APP_DIR . '/core/Database.php';

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    protected $config;

    protected $database;

    protected function setUp(): void
    {
        // Load the configuration from the config file
        $this->config = require CONFIG_DIR . '/config.php';

        // Initialize the database instance
        $this->database = new Database($this->config);
    }

    protected function tearDown(): void
    {
        // Reset the instance after each test to ensure isolation between tests.
        $reflection = new \ReflectionClass(Database::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null);
    }

    /**
     * Initializes the database and creates the necessary tables.
     * Inserts predefined data into the database.
     */
    private function initializeDatabase()
    {
        // Path to the SQL script file
        $sqlFilePath = __DIR__ . '/database/maw11_jdn_test.sql';

        // Read the entire SQL script
        $file = file_get_contents($sqlFilePath);
        if ($file === false) {
            throw new RuntimeException("Unable to read the SQL file: $sqlFilePath");
        }

        // Split the SQL commands by semicolon
        $sqlCommands = explode(';', $file);

        // Execute each SQL command
        foreach ($sqlCommands as $command) {
            $command = trim($command);
            if (!empty($command)) {
                $this->database->querySimpleExecute($command);
            }
        }
    }

    /**
     * Drops the tables from the database to ensure a clean state for each test.
     */
    private function dropTables()
    {
        // Disable foreign key checks temporarily to avoid constraint violations during drop
        $this->database->querySimpleExecute("SET FOREIGN_KEY_CHECKS = 0;");

        // Drop tables in the correct order, from the most dependent (answers) to the least dependent (fields_type)
        $this->database->querySimpleExecute("DROP TABLE IF EXISTS answers;");
        $this->database->querySimpleExecute("DROP TABLE IF EXISTS fulfillments;");
        $this->database->querySimpleExecute("DROP TABLE IF EXISTS fields;");
        $this->database->querySimpleExecute("DROP TABLE IF EXISTS exercises;");
        $this->database->querySimpleExecute("DROP TABLE IF EXISTS status;");
        $this->database->querySimpleExecute("DROP TABLE IF EXISTS fields_type;");

        // Re-enable foreign key checks
        $this->database->querySimpleExecute("SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * Create the tables based on the schema.
     */
    private function createTables()
    {
        $this->database->querySimpleExecute("
        CREATE TABLE IF NOT EXISTS `fields_type` (
            `id_fields_type` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL
        );
    ");

        $this->database->querySimpleExecute("
        CREATE TABLE IF NOT EXISTS `status` (
            `id_status` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL
        );
    ");

        $this->database->querySimpleExecute("
        CREATE TABLE IF NOT EXISTS `exercises` (
            `id_exercises` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `id_status` INT,
            FOREIGN KEY (`id_status`) REFERENCES `status`(`id_status`)
        );
    ");

        $this->database->querySimpleExecute("
        CREATE TABLE IF NOT EXISTS `fields` (
            `id_fields` INT AUTO_INCREMENT PRIMARY KEY,
            `label` VARCHAR(255) NOT NULL,
            `id_exercises` INT,
            `id_fields_type` INT,
            FOREIGN KEY (`id_exercises`) REFERENCES `exercises`(`id_exercises`) ON DELETE CASCADE,
            FOREIGN KEY (`id_fields_type`) REFERENCES `fields_type`(`id_fields_type`)
        );
    ");

        $this->database->querySimpleExecute("
        CREATE TABLE IF NOT EXISTS `fulfillments` (
            `id_fulfillments` INT AUTO_INCREMENT PRIMARY KEY,
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME,
            `id_exercises` INT NOT NULL,
            FOREIGN KEY (`id_exercises`) REFERENCES `exercises`(`id_exercises`) ON DELETE CASCADE
        );
    ");

        $this->database->querySimpleExecute("
        CREATE TABLE IF NOT EXISTS `answers` (
            `id_answers` INT AUTO_INCREMENT PRIMARY KEY,
            `value` TEXT,
            `id_fields` INT NOT NULL,
            `id_fulfillments` INT,
            FOREIGN KEY (`id_fields`) REFERENCES `fields`(`id_fields`) ON DELETE CASCADE,
            FOREIGN KEY (`id_fulfillments`) REFERENCES `fulfillments`(`id_fulfillments`) ON DELETE CASCADE
        );
    ");
    }

    // ===========================
    // SECTION: Database Connection Tests
    // ===========================

    public function testDatabaseConnectionSuccess()
    {
        // Given: A valid configuration for the database
        $config = $this->config;

        // When: A new Database instance is created with the configuration
        $database = new Database($config);

        // Then: The instance should not be null
        $this->assertNotNull($database, "Database instance should not be null when valid configuration is provided.");
    }

    public function testDatabaseConnectionFailure()
    {
        // Given: An invalid configuration that should trigger a connection failure
        $invalidConfig = [
            'db' => [
                'driver' => 'sqlite3',
                'host' => 'localhost',
                'database' => 'bad-name',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ];

        // When: Trying to create a new Database instance with the invalid configuration
        try {
            new Database($invalidConfig);
        } catch (PDOException $e) {
            // Then: A PDOException should be thrown with a message containing 'could not find driver'
            $this->assertStringContainsString('could not find driver', $e->getMessage(), "The PDOException message should indicate a missing driver.");
            return;
        }

        // Then: If no exception is thrown, the test should fail
        $this->fail('Expected PDOException was not thrown.');
    }

    // ===========================
    // SECTION: getInstance Tests
    // ===========================

    public function testGetInstanceCreatesNewInstanceWhenNoneExists()
    {
        // Given: Load the database configuration.
        $config = $this->config;

        // When: Call the getInstance method with the configuration.
        $instance = Database::getInstance($config);

        // Then: Verify that the returned instance is of type Database.
        $this->assertInstanceOf(Database::class, $instance, "getInstance should return a Database instance when none exists.");
    }

    public function testGetInstanceReturnsExistingInstance()
    {
        // Given: Load the database configuration.
        $config = $this->config;

        // Create the first instance.
        $firstInstance = Database::getInstance($config);

        // When: Attempt to get another instance with the same configuration.
        $secondInstance = Database::getInstance($config);

        // Then: Verify that both instances are the same.
        $this->assertSame($firstInstance, $secondInstance, "getInstance should return the same instance if one already exists.");
    }

    // ===========================
    // SECTION: querySimpleExecute Tests
    // ===========================

    public function testQuerySimpleExecuteReturnsEmptyResultWhenTableIsEmpty()
    {
        $this->dropTables();

        // Given: The 'exercises' table is empty
        $this->createTables();

        // When: The querySimpleExecute method is called with a SELECT query.
        $query = 'SELECT * FROM exercises;';
        $statement = $this->database->querySimpleExecute($query);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Then: The result should be an empty array.
        $this->assertEmpty($results, "The query should return an empty result set when the exercises table is empty.");
    }

    public function testQuerySimpleExecuteReturnsDataWhenTableIsNotEmpty()
    {
        // Given: The 'exercises' table is populated with data
        $this->initializeDatabase();

        // When: The querySimpleExecute method is called with a SELECT query.
        $query = 'SELECT * FROM exercises;';
        $statement = $this->database->querySimpleExecute($query);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Then: The result should match the predefined data.
        $expected = [
            ['id_exercises' => '1', 'title' => 'Exercise with Status 1', 'id_status' => '1'],
            ['id_exercises' => '2', 'title' => 'Exercise with Status 2', 'id_status' => '2'],
            ['id_exercises' => '3', 'title' => 'Exercise with Status 3', 'id_status' => '3'],
        ];

        $this->assertEquals($expected, $results, "The query should return all the data from the exercises table.");
    }

    // ===========================
    // SECTION: queryPrepareExecute Tests
    // ===========================

    public function testQueryPrepareExecuteReturnsSingleRecord()
    {
        // Given: The database is already initialized with data
        $this->initializeDatabase();

        // When: The queryPrepareExecute method is called with a parameterized query
        $query = "SELECT * FROM exercises WHERE id_exercises = :id";

        $binds = [
            'id' => ['value' => 1, 'type' => PDO::PARAM_INT]
        ];

        $result = $this->database->queryPrepareExecute($query, $binds);

        // Fetch the results
        $record = $result->fetch(PDO::FETCH_ASSOC);

        // Then: The method must return only the record corresponding to id_exercises = 1
        $expected = [
            'id_exercises' => 1,
            'title' => 'Exercise with Status 1',
            'id_status' => 1
        ];

        $this->assertEquals($expected, $record, "The record corresponding to id_exercises = 1 should be returned.");
    }

    public function testQueryPrepareExecuteReturnsSingleRecordWithStatusName()
    {
        // Given: The database is already initialized with data
        $this->initializeDatabase();

        // When: The queryPrepareExecute method is called with a parameterized query
        $query = "SELECT status.name AS status_name
            FROM exercises
            JOIN status ON status.id_status = exercises.id_status 
            WHERE id_exercises = :id
        ";

        $binds = [
            'id' => ['value' => 1, 'type' => PDO::PARAM_INT]
        ];

        $result = $this->database->queryPrepareExecute($query, $binds);
        $record = $result->fetch(PDO::FETCH_ASSOC);

        // Then: The method must return the status name corresponding to id_exercises = 1
        $expected = [
            'status_name' => 'edit'
        ];

        // Assert that the result matches the expected output
        $this->assertEquals($expected, $record, "The status name corresponding to id_exercises = 1 should be returned.");
    }
}
