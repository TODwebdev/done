<?php
namespace DataProviders;

use Interfaces\MapperInterface;

class RedisExistsMapper  extends MapperAbstract implements MapperInterface
{
    protected $key;

    public function __construct($key)
    {
        if (!is_string($key)) throw new \InvalidArgumentException('Key should be of type string');
        $this->key = $key;
    }

    public function transform()
    {
        return $this->key;
    }
}