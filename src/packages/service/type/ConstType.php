<?php
namespace Status\Type;

/**
 * Class ConstType
 * @package Status\Type
 */
class ConstType
{
    const ARRAY    = 0;
    const BOOL     = 1;
    const BOOLEAN  = 1;
    const FLOAT    = 2;
    const DOUBLE   = 3;
    const INT      = 4;
    const INTEGER  = 4;
    const NULL     = 5;
    const STRING   = 6;
    const STR      = 6;
    /**
     * @var array
     */
    protected $types = [
        'array',
        'boolean',
        'float',
        'double',
        'integer',
        'null',
        'string'
    ];
}