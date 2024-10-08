<?php

use PHPUnit\Framework\TestCase;
use App\Core\Database;

class DatabaseText extends TestCase
{
    private $database;

    public function setUp(): void
    {
        $config = require __DIR__ . '../../../config/config.php';

        $this->database = new Database($config);
    }
}
