<?php
namespace Exceptions;


class CacherInitException extends \RuntimeException
{
    public function __construct($cacherName='')
    {
        parent::__construct(sprintf('Cacher system %s was not initialized', $cacherName));
    }
}