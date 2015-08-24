<?php
namespace Exceptions;


class ConfigKeyException  extends \RuntimeException
{
    public function __construct($key='')
    {
        parent::__construct(sprintf('Config key %s was not found', $key));
    }
}