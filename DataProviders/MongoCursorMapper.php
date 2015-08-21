<?php
namespace DataProviders;


use Interfaces\MapperInterface;

class MongoCursorMapper  extends MapperAbstract implements MapperInterface
{
    protected $cursor;

    public function __construct( $cursor)
    {
        $this->cursor = $cursor;
    }

    public function transform()
    {
        foreach ($this->cursor as $curr) {
           $ret[] = $curr;
        }
        return $ret;
    }

}