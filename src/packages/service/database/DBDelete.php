<?php
namespace Status\Database;

use Status\Database\Founder\DBFounder;

/**
 * Class DBDelete
 * @package Status\Database
 */
class DBDelete extends DBFounder
{
    /**
     * DBDelete constructor.
     * @param string $operation
     * @param string $query
     * @param array $properties
     */
    public function __construct(string $operation, string $query, array $properties = [])
    {
        parent::__construct($operation, $query, $properties);
        $this->get();
    }
}