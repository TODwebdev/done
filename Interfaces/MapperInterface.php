<?php
namespace Interfaces;


interface MapperInterface {

    /**
     * Transforms data and returns structure, required by DataProvider
     * @return mixed
     */
    public function transform();
}