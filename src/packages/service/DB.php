<?php
namespace Status\Service;

use Status\Database\{
    DBSelect, DBInsert, DBDelete, DBUpdate
};

/**
 * Class DB
 * @package Status\Service
 */
final class DB
{
    /**
     * @param string $query
     * @param array $parameters
     * @return DBSelect
     * @throws \Exception
     */
    public static function select(string $query, array $parameters = [])
    {
        return new DBSelect("SELECT", $query, $parameters );
    }

    /**
     * @param string $query
     * @param array $parameters
     * @return DBInsert
     * @throws \Exception
     */
    public static function insert(string $query, array $parameters = [])
    {
        return new DBInsert("INSERT INTO", $query, $parameters);
    }

    /**
     * @param string $query
     * @param array $parameters
     * @return DBDelete
     */
    public static function delete(string $query, array $parameters = [])
    {
        return new DBDelete("DELETE FROM", $query, $parameters);
    }

    /**
     * @param string $query
     * @param array $parameters
     * @return DBUpdate
     */
    public static function update(string $query, array $parameters = [])
    {
        return new DBUpdate("UPDATE", $query, $parameters);
    }
}