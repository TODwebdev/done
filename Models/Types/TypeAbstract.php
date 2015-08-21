<?php
namespace Models\Types;


abstract class TypeAbstract {

    protected $value;

    public function getValue()
    {
        return $this->cast();
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    abstract protected function cast();
}