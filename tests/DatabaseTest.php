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
                'database' => __DIR__ . '/../db/database.sqlite',
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

    public function testGetInstanceCreatesNewInstanceWhenNoneExists()
    {
        // Given: Load the database configuration.
        $config = require __DIR__ . '../../config/config.php';

        // When: Call the getInstance method with the configuration.
        $instance = Database::getInstance($config);

        // Then: Verify that the returned instance is of type Database.
        $this->assertInstanceOf(Database::class, $instance, "getInstance should return a Database instance when none exists.");
    }

    public function testGetInstanceReturnsExistingInstance()
    {
        // Given: Load the database configuration.
        $config = require __DIR__ . '../../config/config.php';

        // Create the first instance.
        $firstInstance = Database::getInstance($config);

        // When: Attempt to get another instance with the same configuration.
        $secondInstance = Database::getInstance($config);

        // Then: Verify that both instances are the same.
        $this->assertSame($firstInstance, $secondInstance, "getInstance should return the same instance if one already exists.");
    }
}
