<?php
namespace DataProviders;

use Interfaces\MapperInterface;
use Models\Condition;

class RedisSetMapper extends MapperAbstract implements MapperInterface
{
    protected $key;

    public function __construct($key, $val)
    {
        $this->key = $key;
        $this->val = $val;
    }

    public function transform()
    {
        return [$this->key, $this->val];
    }
}