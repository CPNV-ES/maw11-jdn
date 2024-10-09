<?php
define('BASE_DIR', dirname(__FILE__) . '/..');
define('APP_DIR', BASE_DIR . '/app');

require_once APP_DIR . '/core/Database.php';

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    protected $config;

    protected function setUp(): void
    {
        // Set up the configuration array
        $this->config = [
            'db' => [
                'driver' => 'sqlite',
                'database' => __DIR__ . '/db/database_test.sqlite',
            ],
        ];
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
     * Optionally inserts predefined data if $insertData is true.
     * 
     * @param bool $insertData If true, inserts predefined data into the exercises table.
     */
    private function initializeDatabase(bool $insertData = false)
    {
        $database = new Database($this->config);

        $sqlFilePath = __DIR__ . '/db/create_database_test.sql';
        $file = file_get_contents($sqlFilePath);

        // Split the SQL commands by semicolon
        $sqlCommands = explode(';', $file);

        // Execute each SQL command
        foreach ($sqlCommands as $command) {
            $command = trim($command);
            if (!empty($command)) {
                $database->querySimpleExecute($command);
            }
        }

        // Insert predefined data if $insertData is true.
        if ($insertData) {
            // Insert data into the 'status' table.
            $database->querySimpleExecute("
                INSERT INTO status (name) VALUES 
                ('edit'),
                ('answering'),
                ('closed');
            ");

            // Insert data into the 'exercises' table, referencing the 'status' table.
            $database->querySimpleExecute("
                INSERT INTO exercises (title, id_status) VALUES 
                ('Exercise with Status 1', 1),
                ('Exercise with Status 2', 2);
            ");
        }
    }

    /**
     * Drops the tables from the database to ensure a clean state for each test.
     */
    private function dropTables($database)
    {
        $database->querySimpleExecute("DROP TABLE IF EXISTS exercises;");
        $database->querySimpleExecute("DROP TABLE IF EXISTS status;");
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
                'driver' => 'sqlite3', // Assuming 'sqlite3' is an invalid driver for this test
                'database' => 'invalid_database', // Invalid database path
            ]
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
        $database = new Database($this->config);

        $this->dropTables($database);

        // Given: The 'exercises' table is empty
        $this->initializeDatabase(false);

        // When: The querySimpleExecute method is called with a SELECT query.
        $query = 'SELECT * FROM exercises;';
        $statement = $database->querySimpleExecute($query);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Then: The result should be an empty array.
        $this->assertEmpty($results, "The query should return an empty result set when the exercises table is empty.");
    }

    public function testQuerySimpleExecuteReturnsDataWhenTableIsNotEmpty()
    {
        $database = new Database($this->config);

        $this->dropTables($database);

        // Given: The 'exercises' table is populated with data
        $this->initializeDatabase(true);

        // When: The querySimpleExecute method is called with a SELECT query.
        $query = 'SELECT * FROM exercises;';
        $statement = $database->querySimpleExecute($query);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Then: The result should match the predefined data.
        $expected = [
            ['id_exercises' => '1', 'title' => 'Exercise with Status 1', 'id_status' => '1'],
            ['id_exercises' => '2', 'title' => 'Exercise with Status 2', 'id_status' => '2']
        ];

        $this->assertEquals($expected, $results, "The query should return all the data from the exercises table.");
    }
}
