<?php
namespace Interfaces;


interface ConfigInterface {

    /**
     * Returns current user's identifier
     * @return string
     */
    public function getUserPwd();
}