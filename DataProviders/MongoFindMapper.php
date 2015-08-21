<?php
namespace DataProviders;


use Interfaces\MapperInterface;
use Models\Condition;
use Models\DataStructures\FindSetup;

class MongoFindMapper extends MapperAbstract implements MapperInterface
{
    protected $findCond;

    public function __construct(FindSetup $findCond)
    {
        $this->findCond = $findCond;
    }

    public function transform()
    {
        $cond = $this->findCond;
        foreach ($cond as $condition) {
            if ($condition instanceof Condition) {
                
            }
        }
        return [];
    }

}