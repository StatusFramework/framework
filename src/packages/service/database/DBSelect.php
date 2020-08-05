<?php
/**
 * Created by PhpStorm.
 * User: Win7
 * Date: 10.01.2020
 * Time: 8:00
 */

namespace Status\Database;

use Status\Database\Founder\DBFounder;

/**
 * Class DBSelect
 * @package Status\Database
 */
class DBSelect extends DBFounder
{
    /**
     * DBSelect constructor.
     * @param string $operation
     * @param string $query
     * @param array $parameters
     * @throws \Exception
     *
     */
    public function __construct(string $operation, string $query, array $parameters)
    {
        parent::__construct($operation, $query, $parameters);
        $this->get();
    }
}