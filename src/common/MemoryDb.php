<?php

namespace David\PhpTest\common;

use Exception;
use PDO;
use PDOException;

class MemoryDb extends PDO
{
    public function __construct()
    {
        try {
            parent::__construct('sqlite::memory:');

            // Set error mode to exceptions for PDO to throw exceptions instead of warnings
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Initialize init script
            $initScript = file_get_contents('./src/migrations/init_script.sql');

            $this->exec($initScript);
        } catch (PDOException $e) {
            die("Error connecting to the database: " . $e->getMessage());
        }
    }
}
