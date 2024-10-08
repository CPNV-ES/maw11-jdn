<?php

require_once APP_DIR . '/core/Database.php';

use PHPUnit\Framework\TestCase;

class DatabaseText extends TestCase
{
    private $database;

    public function setUp(): void
    {
        $config = require __DIR__ . '../../../config/config.php';

        $this->database = new Database($config);
    }
}
