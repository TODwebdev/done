<?php
namespace Interfaces;

/**
 * Concrete classes of this interface guarantee that method getValue returns value of specified type
 * Interface TypesInterface
 * @package Interfaces
 */
interface TypesInterface {

    /**
     * Returns held value
     * @return mixed
     */
    public function getValue();

    /**
     * Sets value but transforms it to the type, defined by class
     * @param $value
     * @return mixed
     * @throw \InvalidArgumentException
     */
    public function setValue($value);
}