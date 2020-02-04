<?php

declare(strict_types=1);

namespace Commander;

use PDO;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected function createPDO(): PDO
    {
        $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=event_store';
        $username = 'root';
        $password = 'password';
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        return new PDO($dsn, $username, $password, $options);
    }
}
