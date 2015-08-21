<?php
namespace Interfaces;


interface DataProviderInterface {

    public function set(MapperInterface $mapper);

    public function get(MapperInterface $mapper);

}