<?php
/**
 * Created by PhpStorm.
 * User: Win7
 * Date: 10.01.2020
 * Time: 8:01
 */

namespace Status\Database\Founder;

use Status\Core\Connection;

/**
 * Class DBFounder
 * @package Status\Database\Founder
 */
class DBFounder
{
    /**
     * @var string
     */
    protected $operation = '';
    /**
     * @var string
     */
    protected $query = '';
    /**
     * @var array 
     */
    protected $properties = [];
    /**
     * @var null|\PDOStatement
     */
    protected $pdo = NULL;

    /**
     * DBFounder constructor.
     * @param string $operation
     * @param string $query
     * @param array $properties
     */
    public function __construct(string $operation, string $query, array $properties = [])
    {
        $this->operation = $operation;
        $this->query = $query;
        $this->properties = array_values($properties);
    }

    protected function get()
    {
        if(empty(Connection::getPDO()))
            throw new \Exception('Error connecting to database', 500);

        $this->pdo = Connection::getPDO()->prepare($this->toQueryString());
        $this->pdo->execute($this->properties);
    }

    protected function getInsert()
    {
        if(empty(Connection::getPDO()))
            throw new \Exception('Error connecting to database', 500);

        $this->pdo = Connection::getPDO()->prepare($this->toQueryString());
        $this->pdo->execute($this->properties);
    }

    /**
     * @return string
     */
    protected function toQueryString()
    {
        return $this->operation . " " . $this->query;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->toQueryString();
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return mixed|object
     */
    public function fetch()
    {
        return $this->pdo->fetch();
    }

    /**
     * @return array
     */
    public function fetchAll(): array
    {
        return $this->pdo->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return mixed
     */
    public function fetchColumn()
    {
        return $this->pdo->fetchColumn();
    }

    /**
     * @return int
     */
    public function rowCount(): int
    {
        return $this->pdo->rowCount();
    }

    /**
     * @return int
     */
    public function columnCount(): int
    {
        return $this->pdo->columnCount();
    }

    /**
     * @return mixed|object
     */
    public function fetchObject()
    {
        return $this->pdo->fetchObject();
    }

    /**
     * @return string
     */
    public function errorCode(): string
    {
        return $this->pdo->errorCode();
    }

    /**
     * @return array
     */
    public function errorInfo(): array
    {
        return $this->pdo->errorInfo();
    }

    /**
     * @return bool
     */
    public function result(): bool
    {
        return $this->pdo->errorCode() == "00000";
    }

    /**
     * @return \PDOStatement|null
     */
    protected function getPdo(): ?\PDOStatement
    {
        return $this->pdo;
    }
}