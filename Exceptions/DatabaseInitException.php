<?php
namespace Exceptions;


class DatabaseInitException  extends \RuntimeException
{
    public function __construct($dbName='')
    {
        parent::__construct(sprintf('Database %s was not initialized', $dbName));
    }
}