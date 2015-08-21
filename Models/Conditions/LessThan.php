<?php
namespace Models\Conditions;

use Interfaces\ConditionInterface;

class LessThan extends ConditionAbstract implements ConditionInterface
{
    public function getStringRepresentation()
    {
        return '<';
    }
}