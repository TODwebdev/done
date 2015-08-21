<?php
namespace Models\Types;


use Interfaces\TypesInterface;

class IntType extends TypeAbstract implements TypesInterface
{
    protected function cast()
    {
        return intval($this->value);
    }
}