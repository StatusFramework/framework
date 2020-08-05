<?php
namespace Status\Core\Performer;

/**
 * Class ReflectionParamsController
 * @package Status\Core\Performer
 */
final class ReflectionParamsController
{
    /**
     * @var array
     */
    private $refArgs = [];
    /**
     * @var array
     */
    private $refParameters = [];
    /**
     * @var array
     */
    private $defNameType = [
        'int', 'bool', 'string', 'array',
        'float', 'double', 'object', NULL
    ];

    /**
     * ReflectionParamsController constructor.
     * @param array $refArgs
     * @throws \Exception
     */
    public function __construct(Array $refArgs)
    {
        $this->refArgs = $refArgs;
        $this->search();
    }

    /**
     *
     */
    private function search()
    {
        foreach ($this->refArgs as $key => $value)
        {

            if(!($value instanceof \ReflectionParameter)){
                throw new \Exception('error ReflectionParameter', 500);
            }

            if($this->isDefType($value->getType())){
                continue;
            }

            $class = $value->getType()->getName();

            $this->checkStatusClass($class);

            $this->refParameters[] = new $class();
        }
    }

    /**
     * @param $type
     * @return bool
     */
    private function isDefType($type): bool
    {
        return in_array($type, $this->defNameType) ? true : false;
    }

    /**
     * @param $class
     * @throws \Exception
     */
    private function checkStatusClass(string $class)
    {

        try {
            $ref = new \ReflectionClass($class);
        }
        catch (\Throwable $e)
        {
            throw new \Exception('it`s not inteface [class: '.$class.']', 500);
        }

        if($ref->isInterface()){
            throw new \Exception('it`s not inteface [class: '.$class.']', 500);
        }
        if($ref->isAbstract()){
            throw new \Exception('it`s not abstract [class: '.$class.']', 500);
        }
        if($ref->isAnonymous()){
            throw new \Exception('it`s not anonymous [class: '.$class.']',500);
        }
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->refParameters;
    }
}