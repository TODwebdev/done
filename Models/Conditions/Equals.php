<?php
namespace Models\Conditions;


use Interfaces\ConditionInterface;

class Equals extends ConditionAbstract implements ConditionInterface
{
    public function getStringRepresentation()
    {
        return '=';
    }
}