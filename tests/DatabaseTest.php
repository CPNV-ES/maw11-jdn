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

    public function testDatabseConnectionSuccess()
    {
        $database = new Database($this->config);

        $this->assertNotNull($database);
    }

    public function testDatabaseConnectionFailure()
    {
        // Set up a configuration array that will trigger a failure
        $invalidConfig = [
            'db' => [
                'driver' => 'sqlite3',
                'database' => 'invalid_database',
            ]
        ];

        // Expect a PDOException
        try {
            new Database($invalidConfig);
        } catch (PDOException $e) {
            $this->assertStringContainsString('could not find driver', $e->getMessage());
            return;
        }

        $this->fail('Expected PDOException was not thrown.');
    }
}
