<?php
namespace Status\Service;

use Status\Type\ConstType;

/**
 * Class Type
 * @package Status\Service
 */
final class Type extends ConstType
{
    /**
     * полученные данные от пользователя
     * @var mixed
     */
    private $value;

    /**
     * допущенные типы данных пользователем
     * @var int[]
     */
    private $type;

    /**
     * Type constructor.
     * @param $value
     * @param int ...$type
     */
    public function __construct($value, int ...$type)
    {
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * @return string
     */
    private function getType()
    {
        return strtolower(gettype($this->value));
    }

    /**
     * проверяем из списка типов
     * @return bool
     */
    public function check()
    {
        $typeValue = $this->getType();

        foreach ($this->type as $key => $value)
        {
            if(!array_key_exists($value, $this->types))
            {
                throw new \Exception("type not found", 500);
            }

            if($this->types[$value] == $typeValue)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function value()
    {
        if(!$this->check())
            throw new \Exception("specified data type does not match", 500);

        return $this->value;
    }
}