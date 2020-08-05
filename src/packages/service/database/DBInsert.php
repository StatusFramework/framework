<?php
/**
 * Created by PhpStorm.
 * User: Win7
 * Date: 10.01.2020
 * Time: 9:23
 */

namespace Status\Database;

use Status\Core\Connection;
use Status\Database\Founder\DBFounder;

/**
 * Class DBInsert
 * @package Status\Database
 */
class DBInsert extends DBFounder
{
    /**
     * DBInsert constructor.
     * @param string $operation
     * @param string $query
     * @param array $properties
     * @throws \Exception
     */
    public function __construct(string $operation, string $query, array $properties = [])
    {
        parent::__construct($operation, $query, $properties);
        $this->getInsert();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return Connection::getPDO()->lastInsertId();
    }
}