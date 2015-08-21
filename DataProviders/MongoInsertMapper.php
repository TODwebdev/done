<?php
namespace DataProviders;


use Interfaces\MapperInterface;
use Models\DataStructures\InsertSetup;

class MongoInsertMapper extends MapperAbstract implements MapperInterface
{
    protected $insert;

    public function __construct(InsertSetup $insert)
    {
        $this->insert = $insert->getInsert();
    }

    public function transform()
    {
        $ret =[];
        foreach ($this->insert as $insert) {
            foreach ($insert as $key=>$val) {
                $ret[$key] = $val;
            }
        }

        return $ret;
    }
}