<?php
namespace Models\Types;


class StringType extends TypeAbstract implements TypesInterface
{
    protected function cast()
    {
        return strval($this->value);
    }
}