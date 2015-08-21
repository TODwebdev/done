<?php
namespace Models\Types;


class FloatType extends TypeAbstract implements TypesInterface
{
    protected function cast()
    {
        return floatval($this->value);
    }
}