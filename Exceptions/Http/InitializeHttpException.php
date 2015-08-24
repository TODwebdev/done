<?php
/**
 * Created by PhpStorm.
 * User: TOD
 * Date: 23.08.2015
 * Time: 13:16
 */

namespace Exceptions\Http;


class InitializeHttpException extends \Exception {

    public function __construct($message)
    {
        if (!is_string($message)
            || empty($message)
        ) {
            $message = 'Unknown http messaage';
        }
        parent::__construct($message);
    }
}