<?php
namespace Models\Conditions;

use Interfaces\TypesInterface;

/**
 * Data transfer class used to encapsulate single condition, like (leftArg $cond $rightArg)
 */
abstract class ConditionAbstract {

    abstract public function getStringRepresentation();
}