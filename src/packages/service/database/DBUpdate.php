<?php
namespace Status\Database;

use Status\Database\Founder\DBFounder;

/**
 * Class DBUpdate
 * @package Status\Database
 */
class DBUpdate extends DBFounder
{
    private $fetch = NULL;
    private $fetchAll = NULL;
    private $fetchObject = NULL;
    private $errorCode = NULL;
    private $errorInfo = NULL;

    /**
     * DBUpdate constructor.
     * @param string $operation
     * @param string $query
     * @param array $properties
     */
    public function __construct(string $operation, string $query, array $properties = [])
    {
        parent::__construct($operation, $query, $properties);
        $this->get();
        $this->pdo->closeCursor();
    }
}