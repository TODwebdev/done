<?php
namespace DataProviders;


use Interfaces\MapperInterface;

class RedisGetMapper extends MapperAbstract implements MapperInterface
{
    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function transform()
    {
        return $this->key;
    }
}