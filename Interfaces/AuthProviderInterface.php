<?php
namespace Interfaces;


interface AuthProviderInterface {

    /**
     * Unique client identifier
     * @return string
     */
    public function getIdentifier();

    /**
     * Set Identifier So It Is remembered between Sessions
     * @params string $pwd user identifier
     * @return mixed
     */
    public function setIdentifier($pwd);
}