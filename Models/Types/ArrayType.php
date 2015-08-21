<?php
namespace Models\Types;

use Interfaces\TypesInterface;

class ArrayType  extends TypeAbstract implements TypesInterface
{
    protected function cast()
    {
        return [$this->value];
    }
}